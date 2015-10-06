'use strict';
angular.module('restApp').

factory('UserService', [
	'$resource',
	function (a) {
		return a('api/user/:id', {}, {
			get: {
				method: 'GET'
			},
			update: {
				method: 'PUT'
			},
			query: {
				method: 'GET'
			},
			default: {
				id: '',
			}
		});
	}
]);