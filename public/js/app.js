'use strict';

if (typeof $ === 'undefined') {
	throw new Error('This application\'s JavaScript requires jQuery');
}

setTimeout(
	function asyncBootstrap() {
		angular.bootstrap(document, ['restApp']);
	}, (100)
);

angular.module('restApp', [
	'ngResource',
	'ngCookies',
	'ngRoute',
	'toaster',
	'ui.bootstrap',
]).

constant("PERPAGE", [10, 20, 50, 100]).

config(['$routeProvider',
	function (a) {
		a.when('/signin', {
			templateUrl: 'views/page/signin.html',
		}).
		otherwise({
			redirectTo: '/'
		});
	}
]).

config([
	'$httpProvider',
	function ($httpProvider) {
		$httpProvider.interceptors.push('authInterceptorService');
	}
]).


/**=========================================================
 * Module: app-service.js
 * appService
 * require angular-cookie
 =========================================================*/
factory('appService', [
	'cookieService', 'storageService',
	function (cookieService, storageService) {
		if (typeof (Storage) !== 'undefined') {
			return storageService;
			// Code for localStorage/sessionStorage.
		} else {
			// Sorry! No Web Storage support..
			return cookieService;
		}
	}
]).


factory('sessionService', function () {
	var exports;
	exports = {
		get: function (key) {
			var record;
			record = JSON.parse(sessionStorage.getItem(key));
			if (!record) {
				return false;
			}
			return new Date().getTime() < record.timestamp && JSON.parse(record.value);
		},
		set: function (key, val, time) {
			var expire, record;
			expire = time ? (time * 60 * 1000) : 864000;
			record = {
				value: JSON.stringify(val),
				timestamp: new Date().getTime() + expire
			};
			sessionStorage.setItem(key, JSON.stringify(record));
			return val;
		},
		unset: function (key) {
			return sessionStorage.removeItem(key);
		}
	};
	return exports;
}).


factory('storageService', function () {
	var exports;
	exports = {
		get: function (key) {
			var val;
			val = localStorage.getItem(key);
			if (!val) {
				return false;
			}
			return JSON.parse(val);
		},
		set: function (key, val) {
			localStorage.setItem(key, JSON.stringify(val));
			return val;
		},
		unset: function (key) {
			return localStorage.removeItem(key);
		},
		remove: function (key) {
			return localStorage.removeItem(key);
		}
	};
	return exports;
}).

factory('cookieService', [
	'$cookieStore',
	function ($cookieStore) {
		var exports;
		exports = {
			get: function (key) {
				var val;
				val = $cookieStore.get(key);
				if (!val) {
					return false;
				}
				return JSON.parse(val);
			},
			set: function (key, val) {
				$cookieStore.put(key, JSON.stringify(val));
				return val;
			},
			unset: function (key) {
				return $cookieStore.remove(key);
			},
			remove: function (key) {
				return $cookieStore.remove(key);
			}
		};
		return exports;
	}
]).

factory('authInterceptorService', [
	'$rootScope', '$q', '$injector', '$location', '$window', 'appService',
	function ($rootScope, $q, $injector, $location, $window, appService) {
		var forEach = angular.forEach;
		var exports;
		exports = {
			request: function (config) {
				config.headers = config.headers || {};
				var token = appService.get('Authorization');
				if (token) {
					config.headers.Authorization = token;
				}
				return config;
			},
			responseError: function (rejection) {
				if (rejection.status === 400) {
					if (rejection.headers()['content-type'] === 'application/json' || rejection.headers()['content-type'] === 'application/json; charset=utf-8') {
						$rootScope.$broadcast('warning', rejection.data.message || 'Bad request!');
						return $q.resolve(rejection);
					}
				}

				if (rejection.status === 500) {
					var errorMessage = 'Error 500: ' + rejection.data.message;
					$rootScope.$broadcast('error', errorMessage);
					return $q.reject(rejection);
				}

				if (rejection.status === 401) {
					appService.unset('Authorization');
					$rootScope.$broadcast('warning', rejection.data.message || 'Required Authorization!');
					$location.path('/signin');
				}
				return $q.reject(rejection);
			}
		};

		return exports;
	}
]).

factory("AuthService", [
	"$http",
	function ($http) {
		var exports;
		exports = {
			login: function (params) {
				return $http.post("/auth", params);
			},
			create: function (params) {
				return $http.post("/api/user", params);
			}
		};
		return exports;
	}
]).


controller('AppCtrl', [
	'$scope', 'toaster',
	function ($scope, toaster) {
		$scope.$on('success', function (event, msg) {
			toaster.pop('success', '', msg);
		});
		$scope.$on('info', function (event, msg) {
			toaster.pop('info', '', msg);
		});
		$scope.$on('warning', function (event, msg) {
			toaster.pop('warning', '', msg);
		});
		$scope.$on('error', function (event, msg) {
			toaster.pop('error', '', msg);
		});
	}
]).

controller("SigninCtrl", [
	"$scope", "$rootScope", "$location", "AuthService", "appService",
	function ($scope, $rootScope, $location, AuthService, appService) {
		$scope.email = "admin@example.com";
		$scope.password = "password";
		$scope.submit = function () {
			$scope.form.$setDirty();
			if ($scope.form.$valid) {
				AuthService.login({
					email: $scope.email,
					password: $scope.password
				}).success(function (res) {
					appService.set('Authorization', res.token);
					var path;
					$rootScope.$broadcast("success", "Welcome!");
					if ($rootScope.isPath && $rootScope.isPath !== "/signin") {
						path = $rootScope.isPath;
					} else {
						path = "/";
					}
					$location.path(path);
				});
			}
		};
	}
])


;