'use strict';
angular.module('restApp').

controller('UsersCtrl', [
	'$scope', '$location', 'PERPAGE', 'resolvedUser', 'UserService',
	function ($scope, $location, PERPAGE, resolvedUser, UserService) {
		$scope.perpage = PERPAGE;
		$scope.q = $location.search().q || '';
		$scope.limit = $location.search().limit || 20;
		$scope.sort = $location.search().sort || '';
		$scope.order = $location.search().order || '';

		$scope.items = resolvedUser;

		$scope.delete = function (id) {
			var x = confirm('Are you sure you want to delete User?');
			if (x) {
				UserService.delete({
					id: id
				}, function () {
					$scope.items = UserService.query();
				});
			}
		};

		$scope.save = function (id) {
			if (id) {
				UserService.update({
						id: id
					}, $scope.user,
					function () {
						$scope.items = UserService.query();
						$scope.clear();
					});
			} else {
				UserService.save($scope.user,
					function () {
						$scope.items = UserService.query();
						$scope.clear();
					});
			}
		};

		$scope.clear = function () {
			$scope.user = UserService.default();
		};

		$scope.shows = function (limit) {
			$scope.limit = limit;
			$scope.items = UserService.query({
				limit: limit,
				q: $scope.q || ''
			});
		};

		$scope.page = function (page) {
			$scope.items = UserService.query({
				page: page,
				limit: $scope.limit || 20,
				q: $scope.q || ''
			});
		};

		$scope.search = function () {
			$scope.items = UserService.query({
				page: 1,
				limit: $scope.limit || 20,
				q: $scope.q || ''
			});
		};

		$scope.sortBy = function (sort) {
			if ($scope.sort !== sort) {
				$scope.sort = sort;
				$scope.order = 'asc';
			} else {
				$scope.order = ($scope.order === 'asc' ? 'desc' : 'asc');
			}
			$scope.items = UserService.query({
				sort: $scope.sort,
				order: $scope.order,
				q: $scope.q
			});
		};
	}
]).

controller('UserCtrl', [
	'$scope', '$location', '$timeout', 'UserService', 'user',
	function ($scope, $location, $timeout, UserService, user) {

		$scope.user = user;

		$scope.submit = function () {
			if ($scope.user.id) {
				UserService.update({
						id: user.id
					}, $scope.user,
					function () {
						$location.path('/users');
					});
			} else {
				UserService.save($scope.user,
					function () {
						$location.path('/users');
					});
			}
		};

		$scope.dismiss = function () {
			$scope.user = user;
		};

		function initMap(latlng) {
			var map = L.map('map').setView(latlng, 13);

			L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
				maxZoom: 18,
				attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
					'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
					'Imagery © <a href="http://mapbox.com">Mapbox</a>',
				id: 'mapbox.streets'
			}).addTo(map);

			var popup = L.popup();

			function onMapClick(e) {
				popup
					.setLatLng(e.latlng)
					.setContent("You clicked the map at " + e.latlng.toString())
					.openOn(map);
			}

			map.on('click', onMapClick);

		}

		$timeout(function () {
			var latlng = $scope.user.latlng.latlng || '[51.505, -0.09]';
			initMap(latlng.split(', '));
		}, 300);

	}
]);