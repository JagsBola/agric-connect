<?php

use App\Models\Db;
use App\Models\User;

class ChatController extends BaseController {

    public function chats($id = null){
        $this->checkAuth();
        $auth = new User(new Db());
        $chats = $auth->getLastMessages(15);

        //$this->dd($chats);
        
        $id = $id ?? $auth->getLastChatPartyId($chats);
        $party =  new User(new Db(), $id);

        $chat = $auth->getChats($party);

        $this->renderView('chats', compact('chats', 'chat', 'party', 'id'));
    }

    public function sendMessage(){

        $this->checkAuth();
        $auth = (new User($this->db));
        $partyId = $this->post('partyId');
        $message = $this->post('message');

        if(empty($partyId) || empty($message)){
            $this->redirectTo('/chats');
        }

        $party = (new User($this->db, $partyId));
        $auth->sendMessage($party, $message);
    
        $this->chats($partyId);
    }
        
}
