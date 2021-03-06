export function RoutesConfig($stateProvider, $urlRouterProvider) {
    'ngInject';

    let getView = (viewName) => {
        return `./views/app/pages/${viewName}/${viewName}.page.html`;
    };

    $urlRouterProvider.otherwise('/');


    $stateProvider
        .state('app', {
            abstract: true,
            data: {},//{auth: true} would require JWT auth
            views: {
                header: {
                    templateUrl: getView('header')
                },
                footer: {
                    templateUrl: getView('footer')
                },
                main: {}
            }
        })
        .state('app.landing', {
            url: '/',
            views: {
                'main@': {
                    templateUrl: getView('landing')
                }
            }
        })
        .state('app.login', {
            url: '/login',
            redirectTo: 'app.users_me',
            views: {
                'main@': {
                    templateUrl: getView('login')
                }
            }
        })
        .state('app.register', {
            url: '/register',
            views: {
                'main@': {
                    templateUrl: getView('register')
                }
            }
        })
        .state('app.users_id', {
            url: '/users/{id:(?:[a-fA-F0-9]{8}(?:-[a-fA-F0-9]{4}){3}-[a-fA-F0-9]{12})}',
            views: {
                'main@': {
                    templateUrl: getView('users')
                }
            }
        })
        .state('app.users_me', {
            url: '/users/self',
            data: {auth: true},
            views: {
                'main@': {
                    templateUrl: getView('users') //???
                }
            }
        })
        .state('app.users_all', {
            url: '/users/all',
            views: {
                'main@': {
                    templateUrl: getView('users')
                }
            }
        })
        .state('app.forgot_password', {
            url: '/forgot-password',
            views: {
                'main@': {
                    templateUrl: getView('forgot-password')
                }
            }
        })
        .state('app.reset_password', {
            url: '/reset-password/:email/:token',
            views: {
                'main@': {
                    templateUrl: getView('reset-password')
                }
            }
        })
        .state('app.event_create', {
            url: '/events/create',
            data: {auth: true},//{auth: true} would require JWT auth
            views: {
                'main@': {
                    templateUrl: getView('create_event')
                }
            }
        })
        .state('app.event_details', {
            url: '/events/{id}',
            views: {
                'main@': {
                    templateUrl: getView('event_details')
                }
            }
        })
        .state('app.calendar_user', {
            url: '/users/{id}/calendar',
            views: {
                'main@': {
                    templateUrl: getView('calendar_user')
                }
            }
        })


    ;
}
