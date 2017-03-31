<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use Auth;



class EventController extends Controller
{
    /**
     * Fonction de creation d'un evenement
     * @param Request $request
     * @return reponse contenant l'evenement si celui-ci a ete cree.
     */
    public function create(Request $request)
    {
        $this->validationEvent($request);
        $event = new Event();
        $event->id = (string)Uuid::generate();

        $event = $this->fillEvent($request, $event);

        if($event->save())
            return response()->created(compact('event'));
        else
            return response()->error("La création a échouée", 500);
    }

    /**
     * Methode permettant de recuperer la liste de tous les evenements
     * @return reponse contenant la liste des evenements si il y en a
     */
    public function findAll(){
        $events = Event::all();

        if($events->count() == 0)
            return response("Aucun contenu", 204)
                ->header('Content-Type', 'application/json');
        else {
            $listEvents = array();
            $user = User::findAuthorOfRequest();
            foreach ($events as $event){
                if($event->isAccessible($user))
                    array_push($listEvents, $event);
            }
            return response()->success(compact('listEvents'));
        }
    }

    /**
     * Methode permettant de recuperer un evenement grace a son id
     * @param $id id de l'evenement a recuperer
     * @return reponse contenant l'evenement si il est trouve, ou une erreur 404 sinon.
     */
    public function findById($id){
        $event = Event::find($id);
        if($event != null){
            if($event->isAccessible())
                return response()->success(compact('event'));
            else
                return response()->error("L'événement est privé", 401);
        }
        else
            return response()->error("Evenement non trouvé", 404);
    }

    /**
     * Methode permettant de supprimer un evenement via son id
     * @param $id id de l'evenement a supprimmer
     */
    public function delete($id){
        $event = Event::find($id);
        if($event != null) {
            $user = Auth::user();
            if($user->isOrganizer($event))
                $event->delete();
            else
                return response()->error("Vous n'avez pas les droits necessaires pour effectuer cette action", 401);
        }
        return response()->success("Evenement supprimé.");
    }

    /**
     * Methode permettant de modifier ou ajouter un event
     * @param Request $request
     * @param $id id de l'event a modifier
     * @return mixed reponse contenant l'evenement modifie ou cree
     */
    public function update(Request $request, $id){
        $this->validationEvent($request);

        $event = Event::find($id);
        $created = false;

        if($event == null){
            $created = true;
            $event = new Event();
            $event->id = (string)Uuid::generate();
        } else {
            $user = Auth::user();
            if(!$user->isOrganizer($event))
                return response()->error("Vous n'avez pas les droits necessaires pour effectuer cette action", 401);
        }

        $event = $this->fillEvent($request, $event, $created);

        if($event->save()){
            if($created)
                return response()->created(compact('event'));
            else
                return response()->success(compact('event'));
        } else
            return response()->error("La modification a échouée", 500);

    }


    /*******************************************************************************************************************
     *******************************************************************************************************************
     **                                                                                                               **
     **                                     Méthodes privées                                                          **
     **                                                                                                               **
     *******************************************************************************************************************
     *******************************************************************************************************************/

    /**
     * Methode privee permettant de remplir les attribut d'un event lors
     * de la modification ou la creation d'un event
     * @param Request $request
     * @param $event evenement a completer
     * @return mixed evenement complete
     */
    private function fillEvent(Request $request, $event, $createOrganizer = true){
        $event->title = trim($request->input('title'));
        $event->description = trim($request->input('description'));
        $event->public = $request->input('public');
        $event->capacity = $request->input('capacity');
        $event->dateDebut = $request->input('dateDebut');
        $event->dateFin = $request->input('dateFin');
        $event->idCategorie = $request->input('idCategorie');
        if($createOrganizer){
          $event->organizers()->attach(Auth::user()->id);
        }
         $event->placeId = $request->input('placeId');


        return $event;
    }

    /**
     * Methode prive permettant de valider une requete lors de
     * la modification ou la creation d'un event
     * @param Request $request
     */
    private function validationEvent(Request $request){
        $this->validate($request, [
            'title' => 'required | min: 3',
            'description' => 'required | min: 15',
            'public' => 'required | boolean',
            'capacity' => 'required | integer',
            'dateDebut' => 'required|date_format:Y-m-d H:i:s|after:'.date("Y-m-d H:i:s"),
            'dateFin' => 'required|date_format:Y-m-d H:i:s|after:dateDebut',
            'idCategorie' => 'required | string',
            'placeId' => 'required'
        ]);
    }

}
