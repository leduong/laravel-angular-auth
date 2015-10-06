'use strict';
angular.module('restApp').

config([
	'$routeProvider',
	function (a) {
		a.when('/users', {
			templateUrl: 'views/user/users.html',
			controller: 'UsersCtrl',
			resolve: {
				resolvedUser: ['UserService', function (a) {
					return a.query();
				}]
			}
		}).when('/user/:id', {
			templateUrl: 'views/user/user.html',
			controller: 'UserCtrl',
			resolve: {
				user: ['UserService', '$route', function (a, b) {
					return (parseInt(b.current.params.id) > 0) ? a.get({
						id: b.current.params.id
					}) : a.default();
				}]
			}
		})
	}
]);