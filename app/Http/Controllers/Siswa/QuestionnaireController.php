<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use App\Models\SdqAnswer;
use App\Models\Psc17Answer;
use App\Models\SassvAnswer;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function __construct(private ScoringService $scoring) {}

    // Halaman intro assessment
    public function index()
    {
        $existing = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        $draft = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->latest()
            ->first();

        return view('siswa.questionnaire.index', compact('existing', 'draft'));
    }

    // ============================================================
    // SDQ – One question per screen
    // ============================================================
    public function sdq(Request $request)
    {
        $questions = Question::where('type', 'sdq')->orderBy('number')->get();
        $step      = (int) $request->query('step', 1);
        $step      = max(1, min($step, $questions->count()));
        $question  = $questions->firstWhere('number', $step);

        // Ambil jawaban tersimpan di session
        $saved = session('sdq_answers', []);

        return view('siswa.questionnaire.wizard', [
            'instrument' => 'SDQ',
            'instrument_key' => 'sdq',
            'questions'  => $questions,
            'question'   => $question,
            'step'       => $step,
            'total'      => $questions->count(),
            'saved'      => $saved,
            'prev_route' => $step > 1
                ? route('siswa.questionnaire.sdq', ['step' => $step - 1])
                : route('siswa.questionnaire.index'),
            'next_step'  => $step < $questions->count() ? $step + 1 : null,
            'options'    => [
                0 => 'Tidak Benar',
                1 => 'Agak Benar',
                2 => 'Benar',
            ],
        ]);
    }

    public function storeSdqStep(Request $request)
    {
        $step   = (int) $request->input('step');
        $answer = $request->input('answer');

        if ($answer === null || $answer === '') {
            return back()->withErrors(['answer' => 'Harap pilih salah satu jawaban.'])->withInput();
        }

        $saved          = session('sdq_answers', []);
        $saved[$step]   = (int) $answer;
        session(['sdq_answers' => $saved]);

        $total = Question::where('type', 'sdq')->count();

        if ($step < $total) {
            return redirect()->route('siswa.questionnaire.sdq', ['step' => $step + 1]);
        }

        // Semua soal SDQ selesai → simpan ke DB
        return $this->commitSdq($saved);
    }

    private function commitSdq(array $answers)
    {
        $request = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        SdqAnswer::where('response_id', $request->id)->delete();
        foreach ($answers as $num => $val) {
            SdqAnswer::create([
                'response_id'     => $request->id,
                'question_number' => $num,
                'answer_value'    => $val,
            ]);
        }

        session()->forget('sdq_answers');
        return redirect()->route('siswa.questionnaire.psc17', ['step' => 1]);
    }

    // ============================================================
    // PSC-17 – One question per screen
    // ============================================================
    public function psc17(Request $request)
    {
        $questions = Question::where('type', 'psc17')->orderBy('number')->get();
        $step      = (int) $request->query('step', 1);
        $step      = max(1, min($step, $questions->count()));
        $question  = $questions->firstWhere('number', $step);
        $saved     = session('psc17_answers', []);

        return view('siswa.questionnaire.wizard', [
            'instrument'     => 'PSC-17',
            'instrument_key' => 'psc17',
            'questions'      => $questions,
            'question'       => $question,
            'step'           => $step,
            'total'          => $questions->count(),
            'saved'          => $saved,
            'prev_route'     => $step > 1
                ? route('siswa.questionnaire.psc17', ['step' => $step - 1])
                : route('siswa.questionnaire.sdq', ['step' => 25]),
            'next_step'      => $step < $questions->count() ? $step + 1 : null,
            'options'        => [
                0 => 'Tidak Pernah',
                1 => 'Kadang-kadang',
                2 => 'Sering',
            ],
        ]);
    }

    public function storePsc17Step(Request $request)
    {
        $step   = (int) $request->input('step');
        $answer = $request->input('answer');

        if ($answer === null || $answer === '') {
            return back()->withErrors(['answer' => 'Harap pilih salah satu jawaban.'])->withInput();
        }

        $saved        = session('psc17_answers', []);
        $saved[$step] = (int) $answer;
        session(['psc17_answers' => $saved]);

        $total = Question::where('type', 'psc17')->count();

        if ($step < $total) {
            return redirect()->route('siswa.questionnaire.psc17', ['step' => $step + 1]);
        }

        return $this->commitPsc17($saved);
    }

    private function commitPsc17(array $answers)
    {
        $response = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'draft')->latest()->firstOrFail();

        Psc17Answer::where('response_id', $response->id)->delete();
        foreach ($answers as $num => $val) {
            Psc17Answer::create([
                'response_id'     => $response->id,
                'question_number' => $num,
                'answer_value'    => $val,
            ]);
        }

        session()->forget('psc17_answers');
        return redirect()->route('siswa.questionnaire.sassv', ['step' => 1]);
    }

    // ============================================================
    // SAS-SV – One question per screen
    // ============================================================
    public function sassv(Request $request)
    {
        $questions = Question::where('type', 'sassv')->orderBy('number')->get();
        $step      = (int) $request->query('step', 1);
        $step      = max(1, min($step, $questions->count()));
        $question  = $questions->firstWhere('number', $step);
        $saved     = session('sassv_answers', []);

        return view('siswa.questionnaire.wizard', [
            'instrument'     => 'SAS-SV',
            'instrument_key' => 'sassv',
            'questions'      => $questions,
            'question'       => $question,
            'step'           => $step,
            'total'          => $questions->count(),
            'saved'          => $saved,
            'prev_route'     => $step > 1
                ? route('siswa.questionnaire.sassv', ['step' => $step - 1])
                : route('siswa.questionnaire.psc17', ['step' => 17]),
            'next_step'      => $step < $questions->count() ? $step + 1 : null,
            'options'        => [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
                6 => 'Sangat Setuju Sekali',
            ],
        ]);
    }

    public function storeSassvStep(Request $request)
    {
        $step   = (int) $request->input('step');
        $answer = $request->input('answer');

        if ($answer === null || $answer === '') {
            return back()->withErrors(['answer' => 'Harap pilih salah satu jawaban.'])->withInput();
        }

        $saved        = session('sassv_answers', []);
        $saved[$step] = (int) $answer;
        session(['sassv_answers' => $saved]);

        $total = Question::where('type', 'sassv')->count();

        if ($step < $total) {
            return redirect()->route('siswa.questionnaire.sassv', ['step' => $step + 1]);
        }

        return $this->commitSassv($saved);
    }

    private function commitSassv(array $answers)
    {
        $response = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'draft')->latest()->firstOrFail();

        SassvAnswer::where('response_id', $response->id)->delete();
        foreach ($answers as $num => $val) {
            SassvAnswer::create([
                'response_id'     => $response->id,
                'question_number' => $num,
                'answer_value'    => $val,
            ]);
        }

        session()->forget('sassv_answers');

        // Hitung skor & klasifikasi
        $this->scoring->calculate($response);

        return redirect()->route('siswa.questionnaire.result', $response->id);
    }

    // Halaman hasil
    public function result($id)
    {
        $response = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.questionnaire.result', compact('response'));
    }
}