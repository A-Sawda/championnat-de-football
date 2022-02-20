<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Cookie;
use App\Repositories\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function showRanking()
    {
        $ranking = $this->repository->sortedRanking();
        $cookieTeam=Cookie::get('followed_team');
    return view('ranking', ['ranking' => $ranking, 'cookieTeam' =>$cookieTeam]);
    }

    public function showTeam(int $teamId)
    {
        $teamMatches = $this->repository->teamMatches($teamId);
        $rankingRow = $this->repository->rankingRow($teamId);
        return view('team', ['rankingRow' => $rankingRow, 'teamMatches' => $teamMatches]);
    }

    public function createTeam(Request $request)
    {
        if (!$request->session()->has('user')) {
        return redirect(route('login'));
        }
        return view('team_create');
    }

    public function storeTeam(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }
        $messages = [
            'team_name.required' => "Vous devez saisir un nom d'équipe.",
            'team_name.min' => "Le nom doit contenir au moins :min caractères.",
            'team_name.max' => "Le nom doit contenir au plus :max caractères.",
            'team_name.unique' => "Le nom d'équipe existe déjà."
          ];
        $rules = ['team_name' => ['required', 'min:3', 'max:20', 'unique:teams,name']];
        $validatedData = $request->validate($rules, $messages);
        
       // return $request->input('team_name'); //affiche le nom de la team 
        //return redirect()->route('teams.show', ['teamId' => $teamId]);
        try {
            // appels aux méthodes de l'objet de la classe Repository
            $teamId=$this->repository->insertTeam(["name"=>$validatedData['team_name']]);
        $this->repository->updateRanking();
        return redirect()->route('teams.show', ['teamId' => $teamId]);
          } catch (Exception $exception) {
              //L'erreur que ca affiche L'équipe n'a pas pu être ajoutée ☹
            return redirect()->route('teams.create')->withInput()->withErrors("Impossible de créer l'équipe.");
          }
    }

    public function createMatch(Request $request)
    {
        $teams=$this->repository->teams();
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }
        return view('match_create', ['teams'=>$teams]);
    }

    public function storeMatch(Request $request) 
    {
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }
        $messages = [
            'team0.required' => 'Vous devez choisir une équipe.',
            'team0.exists' => 'Vous devez choisir une équipe qui existe.',
            'team1.required' => 'Vous devez choisir une équipe.',
            'team1.exists' => 'Vous devez choisir une équipe qui existe.',
            'date.required' => 'Vous devez choisir une date.',
            'date.date' => 'Vous devez choisir une date valide.',
            'time.required' => 'Vous devez choisir une heure.',
            'time.date_format' => 'Vous devez choisir une heure valide.',
            'score0.required' => 'Vous devez choisir un nombre de buts.',
            'score0.integer' => 'Vous devez choisir un nombre de buts entier.',
            'score0.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',
            'score1.required' => 'Vous devez choisir un nombre de buts.',
            'score1.integer' => 'Vous devez choisir un nombre de buts entier.',
            'score1.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',
        ];

        $rules = [
            'team0' => ['required', 'exists:teams,id'],
            'team1' => ['required', 'exists:teams,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'score0' => ['required', 'integer', 'between:0,50'],
            'score1' => ['required', 'integer', 'between:0,50']
        ];

        $validatedData = $request->validate($rules, $messages);

        $date = $validatedData['date'];
        $time = $validatedData['time'];
        $datetime = "$date $time";
        try {
            $matchId=$this->repository->insertMatch([
                "team0"=>$validatedData['team0'],
                "team1"=>$validatedData['team1'],
                "score0"=>$validatedData['score0'],
                "score1"=>$validatedData['score1'],
                "date"=>$datetime
            ]);
        $this->repository->updateRanking();
        //$ranking = $this->repository->sortedRanking();
        return redirect()->route('ranking.show');
          } catch (Exception $exception) {
            return 
            //$exception->getMessage();
            redirect()->route('matches.create')->withInput()->withErrors("Impossible de créer le match.");
          }
       
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request, Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'email.exists' => "Cet utilisateur n'existe pas.",
            'password.required' => "Vous devez saisir un mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);
        try {
        # TODO 1 : lever une exception si le mot de passe de l'utilisateur n'est pas correct
        $email=$validatedData['email'];
        $password=$validatedData['password'];
        $value=$this->repository->getUser($email, $password);
        # TODO 2 : se souvenir de l'authentification de l'utilisateur
        $key='user';
        $request->session()->put($key, $value);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
        }
        return redirect()->route('ranking.show');
    }

    public function followTeam(int $teamId)
    {
        return redirect()->route('ranking.show')->cookie('followed_team', $teamId);
    }

    public function logout(Request $request) {
        $request->session()->forget('user');
        return redirect()->route('ranking.show');
    }

    public function showdeleteMatchForm(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }
        $teams=$this->repository->teams();
        return view('deleteMatch', ['teams'=>$teams]);
    }

    public function deleteMatch(Request $request, Repository $repository)
    {
        
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }

        $messages = [
            'team0.required' => 'Vous devez choisir une équipe.',
            'team0.exists' => 'Vous devez choisir une équipe qui existe.',
            'team1.required' => 'Vous devez choisir une équipe.',
            'team1.exists' => 'Vous devez choisir une équipe qui existe.',
        ];

        $rules = [
            'team0' => ['required', 'exists:teams,id'],
            'team1' => ['required', 'exists:teams,id'],
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            $team0=$validatedData['team0'];
            $team1=$validatedData['team1'];
            $this->repository->deleteMatch($team0, $team1);
            return redirect()->route('ranking.show');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
            }
            
    }

    public function showchangePasswordForm()
    {
        return view('changePassword');
    }

    public function changePassword(Request $request, Repository $repository)
    {
        
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }

        $rules = [
            'email' => ['required', 'email', 'exists:users,email'],
            'oldPassword' => ['required'],
            'newPassword' => ['required']
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'email.exists' => "Cet utilisateur n'existe pas.",
            'oldPassword.required' => "Vous devez saisir un mot de passe.",
            'newPassword.required' => "Vous devez saisir un nouveau mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);

        try {
            $email=$validatedData['email'];
            $oldPassword=$validatedData['oldPassword'];
            $newPassword=$validatedData['newPassword'];
            $this->repository->changePassword($email, $oldPassword, $newPassword);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
        }
            return redirect()->route('ranking.show');
    }

    public function showaddUserForm()
    {
        return view('addUser');
    }

    public function addUser(Request $request, Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.unique' => "L'email existe déjà.",
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'password.required' => "Vous devez saisir un mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);

        try {
            $email=$validatedData['email'];
            $password=$validatedData['password'];
            $this->repository->addUser($email, $password);
            return redirect()->route('ranking.show');
        } catch (Exception $e) {
            return redirect()->route('addUser')->withInput()->withErrors("Impossible de vous inscrire.");
        }
            
    }


    public function showdeleteTeamForm(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }
        $teams=$this->repository->teams();
        return view('deleteTeam', ['teams'=>$teams]);
    }

    public function deleteTeam(Request $request, Repository $repository)
    {
        
        if (!$request->session()->has('user')) {
            return redirect(route('login'));
        }

        $messages = [
            'team0.required' => 'Vous devez choisir une équipe.',
            'team0.exists' => 'Vous devez choisir une équipe qui existe.',
        ];

        $rules = [
            'team0' => ['required', 'exists:teams,id'],
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            $team0=$validatedData['team0'];
            $this->repository->deleteTeam($team0);
            return redirect()->route('ranking.show');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
        }
            
    }


}


