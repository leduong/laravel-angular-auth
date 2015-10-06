'use strict';
angular.module('restApp').

controller('UsersCtrl', [
	'$scope', '$modal', '$location', 'PERPAGE', 'resolvedUser', 'UserService',
	function ($scope, $modal, $location, PERPAGE, resolvedUser, UserService) {
		$scope.perpage = PERPAGE;
		$scope.limit = 0;
		$scope.q = $location.search().q || '';
		$scope.role = $location.search().role || '';
		$scope.sort = $location.search().sort || '';
		$scope.order = $location.search().order || '';

		$scope.items = resolvedUser;

		$scope.create = function () {
			$scope.clear();
			$scope.open();
		};

		$scope.update = function (id) {
			$scope.user = UserService.get({
				id: id
			});
			$scope.open(id);
		};

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

		$scope.open = function (id) {
			var modal = $modal.open({
				templateUrl: 'views/user/user-modal.html',
				controller: [
					'$scope', '$modalInstance', 'user',
					function ($scope, $modalInstance, user) {
						$scope.user = user;
						$scope.submit = function () {
							$modalInstance.close($scope.user);
						};
						$scope.dismiss = function () {
							$modalInstance.dismiss('dismiss');
						};
					}
				],
				resolve: {
					user: function () {
						return $scope.user;
					}
				}
			});

			modal.result.then(function (entity) {
				$scope.user = entity;
				$scope.save(id);
			});
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


	}
]).

controller('UserCtrl', [
	'$scope', '$location', 'UserService', 'user',
	function ($scope, $location, UserService, user) {
		// console.log(user);
		$scope.user = user;

		$scope.open = function ($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened = true;
		};

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

	}
]);