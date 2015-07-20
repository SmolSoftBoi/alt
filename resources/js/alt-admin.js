alt.controller('adminDashboard', ['$scope', '$filter', '$http', 'url', function ($scope, $filter, $http, url) {
	$scope.loading = true;
	$scope.active = false;

	$http.get(url.siteUrl('api/status/readstatuses'), {
		params: {
			'config': {
				'limit': 20
			}
		}
	}).success(function (data) {
		$scope.statuses = data;

		$scope.loading = false;
		$scope.active = true;
	});

	$scope.sortStatuses = function (field, reverse) {
		$scope.statuses = $filter('orderBy')($scope.statuses, field, reverse);
	};
}]);

alt.controller('adminStatuses', ['$scope', '$filter', '$http', 'status', 'url', function ($scope, $filter, $http, status, url) {
	$scope.loading = true;
	$scope.active = false;
	$scope.activeStatusKey;

	status.readStatuses().success(function (data) {
		data = status.parse(data);

		$scope.statuses = data;

		$scope.loading = false;
		$scope.active = true;
	});

	$scope.sortStatuses = function (field, reverse) {
		$scope.statuses = $filter('orderBy')($scope.statuses, field, reverse);
	};

	$scope.setActiveStatusKey = function (statusKey) {
		$scope.activeStatusKey = statusKey;
	};
}]);

alt.controller('adminUsers', ['$scope', '$filter', '$http', 'url', function ($scope, $filter, $http, url) {
	$scope.loading = true;
	$scope.active = false;
	$scope.activeUserKey;

	$http.get(url.siteUrl('api/user/readusers')).success(function (data) {
		$scope.users = data;

		$scope.loading = false;
		$scope.active = true;
	});

	$scope.sortUsers = function (field, reverse) {
		$scope.users = $filter('orderBy')($scope.users, field, reverse);
	};

	$scope.setActiveUserKey = function (userKey) {
		$scope.activeUserKey = userKey;
	};
}]);