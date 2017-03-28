class UsersController {
    constructor(API, ToastService, $state, $log) {
        'ngInject';

        this.API = API;
        this.$state = $state;
        this.ToastService = ToastService;
        this.user = null;
        this.$log = $log;

    }

    findOneUser() {
        let id = this.$state.params.id;

        this.API.all('users/' + id).get('').then((response) => {
            this.user =  response.data.user;
            this.$log.log( response.data.user);
        });
    }

    findAllUsers() {
        this.API.all('users').get('').then((response) => {
            // this.user =  response.data.user;
            this.$log.log(response.data);
        });
    }

    findMe() {
        this.API.all('users/self').get('').then((response) => {
            this.user = response.data.user;
            this.$log.log( response.data.user);
        });
    }

    /**
     * Methode de base , permettant de variables les bonnes variables pour la vue, celle ci fait le meme traitement
     * pour afficher les bonnes DIV HTML
     */
    $onInit() {

        switch (this.$state.$current.self.name) {
            case "app.users_all" :
                this.findAllUsers();
                break;
            case "app.users_id" :
                this.findOneUser();
                break;
            case "app.users_me" :
                this.findMe();
                break;
        }

        $("#carousel").slick()

    }


}

export const UsersComponent = {
    templateUrl: './views/app/components/users/users.component.html',
    controller: UsersController,
    controllerAs: 'vm',
    bindings: {}
}
