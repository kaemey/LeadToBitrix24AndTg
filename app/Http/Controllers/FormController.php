<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Form;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    private $tgToken;
    private $chat_id;
    private $data;

    public function index()
    {
        return view('form');
    }
    public function store(StoreRequest $request)
    {
        $this->data = $request->validated();
        Form::create($this->data);

        $paUrl = 'https://b24-v4u98f.bitrix24.ru';
        $whId = 'j9egv13zgfpwvo1k';

        $this->tgToken = '5590847189:AAEEymCadyqoIlEqwK_25C3vfPUaFK5H31M';
        $this->chat_id = '1234';

        $this->sendBitrix($paUrl . '/rest/1/' . $whId . '/', 'crm.lead.add');

        return redirect('/');
    }

    public function sendDataToTelegram()
    {
        $tgUrl = 'https://api.telegram.org/bot' . $this->tgToken . '/';

        $text =
            'ФИО: ' . $this->data['name'] . '
        Телефон: ' . $this->data['phone'] . '
        Дата рождения: ' . $this->data['date'] . '
        Email: ' . $this->data['email'] . '
        Комментарий: ' . $this->data['comment'] . '
        ' . $this->data['bitrixUrl'];

        $response['chat_id'] = $this->chat_id;
        $response['text'] = $text;

        $ch = curl_init($tgUrl . 'sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendErrorToTelegram()
    {
        $tgUrl = 'https://api.telegram.org/bot' . $this->tgToken . '/';

        $text = 'Ошибка добавления лида в Bitrix';

        $response = ['chat_id' => $this->chat_id, 'text' => $text];
        $ch = curl_init($tgUrl . 'sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendBitrix($webhook, $method)
    {
        $bitrixUrl = $webhook . $method . ".json?FIELDS[TITLE]=Lead&FIELDS[NAME]=" . $this->data['name'] . "&FIELDS[EMAIL]=" . $this->data['email'] . "&FIELDS[PHONE]=" . $this->data['phone'] . "&FIELDS[BIRTHDATE]=" . $this->data['date'] . "&FIELDS[COMMENTS]=" . $this->data['comment'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $bitrixUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HEADER, false);

        $res = curl_exec($ch);

        $res = json_decode($res, true);

        curl_close($ch);

        if (isset($res['error'])) {
            $this->sendErrorToTelegram();
        } else {
            $this->data['bitrixUrl'] = "https: //b24-v4u98f.bitrix24.ru/crm/lead/details/" . $res['result'] . "/";
            $this->sendDataToTelegram();
        }
    }

}