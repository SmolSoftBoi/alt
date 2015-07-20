var alt = angular.module('alt', ['ngAnimate', 'ngMessages']);

alt.run(['$rootScope', 'color', 'url', function ($rootScope, color, url) {
	$rootScope.alt = {
		baseUrl: 'https://epickris.com/alt/',
		defaultColorHex: '337ab7'
	};

	$rootScope.alt.defaultColorHSL = color.colorHexToHSL($rootScope.alt.defaultColorHex);
	$rootScope.alt.colorHex = $rootScope.alt.defaultColorHex;
	$rootScope.alt.topColorHex = $rootScope.alt.colorHex;

	$rootScope.url = url;

	$rootScope.setColorHex = function (colorHex) {
		$rootScope.alt.colorHex = colorHex;

		colorHSL = color.colorHexToHSL(colorHex);

		$rootScope.alt.correctedColorHex = color.colorHSLToHex(colorHSL.h, $rootScope.alt.defaultColorHSL.s, $rootScope.alt.defaultColorHSL.l);
	};
}]);

alt.controller('alt', ['$rootScope', '$scope', '$attrs', 'color', function ($rootScope, $scope, $attrs, color) {
	if ($attrs.ngAuthed)
	{
		if ($attrs.ngAuthed == 'true') {
			$rootScope.authed = true;
		} else {
			$rootScope.authed = false;
		}
	}

	if ($attrs.ngColorHex) $rootScope.setColorHex($attrs.ngColorHex);
}]);

alt.controller('statuses', ['$rootScope', '$scope', '$attrs', '$http', '$interval', '$q', '$window', 'status', 'url', function ($rootScope, $scope, $attrs, $http, $interval, $q, $window, status, url) {
	$scope.loading = false;

	var createConfig = function (config) {
		if ($attrs.ngUserId) {
			if (config.where) {
				config.where.user_id = $attrs.ngUserId;
			} else {
				config.where = {
					user_id: $attrs.ngUserId
				}
			}
		}

		return config;
	};

	var run = true;

	var statusId = {};

	var config = createConfig({
		limit: 20
	});

	var asyncStatuses = status.readStatuses(config);

	asyncStatuses.success(function (data) {
		data = status.parse(data);

		status.addStatuses(data);

		statusId = {
			update: data[0].statusId,
			load: data[data.length - 1].statusId
		};
	});

	var vote = function (statusId, vote) {
		if ($rootScope.authed == true) {
			var asyncStatusVote = status.updateStatus(statusId, {
				vote: {
					vote: vote
				}
			});

			asyncStatusVote.success(function (data) {
				data = status.parse(data);

				status.addStatus(data);
			});
		} else {
			$window.location = url.siteUrl('signup');
		}
	};

	$scope.voteUp = function (statusId) {
		vote(statusId, 1);
	};

	$scope.voteDown = function (statusId) {
		vote(statusId, -1);
	};

	$scope.load = function () {
		if (run == false || ! statusId.load || $scope.loading == true) return;

		$scope.loading = true;

		config = createConfig({
			where: {
				'status_id <': statusId.load
			},
			limit: 20
		});

		var asyncStatusesLoad = status.readStatuses(config);

		asyncStatusesLoad.success(function (data) {
			data = status.parse(data);

			if (data != null) {
				status.addStatuses(data);

				statusId.load = data[data.length - 1].statusId;
			} else {
				run = false;
			}

			$scope.loading = false;
		});
	};

	$interval(function () {
		if ( ! statusId.update);

		if (asyncStatusesUpdate) asyncStatusesUpdate.resolve();

		config = createConfig({
			where: {
				'status_id >': statusId.update
			}
		});

		var asyncStatusesUpdate = status.readStatuses(config);

		asyncStatusesUpdate.success(function (data) {
			data = status.parse(data);

			if (data != null) {
				status.addStatuses(data, true);

				statusId.update = data[0].statusId;
			}
		});
	}, 1000 * 60 * 0.2);
}]);

alt.controller('post', ['$rootScope', '$scope', 'status', function ($rootScope, $scope, status) {
	$scope.submitPost = function ($event) {
		$event.preventDefault();

		var asyncStatus = status.createStatus({
			status: $scope.status.status
		});

		$scope.status.status = null;

		angular.element(document.getElementById('post-modal')).modal('hide');

		asyncStatus.success(function (data) {
			data = status.parse(data);

			status.addStatus(data);
		});
	};
}]);

alt.controller('settings', ['$rootScope', '$scope', '$attrs', '$http', 'color', 'url', function ($rootScope, $scope, $attrs, $http, color, url) {
	$scope.user = {
		userId: $attrs.ngUserId
	};

	$scope.$watch(function () {
		return $scope.user.colorHex;
	}, function () {
		var colorHex = String($scope.user.colorHex);

		if (colorHex != 'undefined') colorHex = colorHex.replace('#', '');

		if (colorHex != 'undefined') $scope.user.colorHex = colorHex;

		$rootScope.setColorHex($scope.user.colorHex);
	});

	$http.get(url.siteUrl('api/user/readuser'), {
		params: {
			userId: $scope.user.userId
		}
	}).success(function (data) {
		$scope.user = data;
	});

	$scope.randomColorHex = function() {
		var colorHSL = color.colorHexToHSL(color.colorRandomHex());

		$scope.user.colorHex = color.colorHSLToHex(colorHSL.h, $rootScope.alt.defaultColorHSL.s, $rootScope.alt.defaultColorHSL.l);
	};
}]);

alt.directive('ngScroll', ['$window', function ($window) {
	return {
		restrict: 'A',
		link: function (scope, element, attrs, ctrl) {
			angular.element(window).scroll(function () {
				if (angular.element(window).scrollTop() >= angular.element(document).height() - angular.element(window).height()) scope.$apply(attrs.ngScroll);
			});
		}
	};
}]);

alt.directive('ngMatch', function () {
	return {
		require: '^ngModel',
		restrict: 'A',
		link: function (scope, element, attrs, ctrl) {
			ctrl.$parsers.unshift(function (value) {
				var valid = scope.$eval(attrs.ngMatch).$viewValue == value;

				ctrl.$setValidity('match', valid);

				if (valid) return value;

				return false;
			});

			scope.$watch(attrs.ngMatch, function () {
				ctrl.$setViewValue(ctrl.$viewValue);
			});
		}
	};
});

alt.directive('ngAllowed', ['$http', '$q', function ($http, $q) {
	return {
		require: '^ngModel',
		restrict: 'A',
		link: function (scope, element, attrs, ctrl) {
			var cancel = $q.defer();

			scope.$watch(function () {
				return ctrl.$viewValue;
			}, function () {
				cancel.resolve();
				cancel = $q.defer();

				$http.get('/alt/api/validation/allowed', {
					params: {
						field: attrs.ngUnique,
						value: ctrl.$viewValue,
					},
					timeout: cancel.promise
				}).success(function (data, status, config, headers) {
					var valid = data.valid;
				
					ctrl.$setValidity('allowed', valid);
				}).error(function (data, status, config, headers) {
					ctrl.$setValidity('allowed', null);
				});
			});
		}
	};
}]);

alt.directive('ngUnique', ['$http', '$q', function ($http, $q) {
	return {
		require: '^ngModel',
		restrict: 'A',
		link: function (scope, element, attrs, ctrl) {
			var cancel = $q.defer();

			scope.$watch(function () {
				return ctrl.$viewValue;
			}, function () {
				cancel.resolve();
				cancel = $q.defer();

				if (ctrl.$viewValue == attrs.ngExclude) {
					ctrl.$setValidity('unique', true);
				} else {
					$http.get('/alt/api/validation/unique', {
						params: {
							field:   attrs.ngUnique,
							value:   ctrl.$viewValue,
							exclude: attrs.ngExclude
						},
						timeout: cancel.promise
					}).success(function (data, status, config, headers) {
						var valid = data.valid;
				
						ctrl.$setValidity('unique', valid);
					}).error(function (data, status, config, headers) {
						ctrl.$setValidity('unique', null);
					});
				}
			});
		}
	};
}]);

alt.directive('ngTrianglify', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function (scope, element, attrs, ctrl) {
			scope.$watch(function () {
				return attrs.ngTrianglify;
			}, function () {
				var opts = {
					width: parseInt(element.css('width')),
					height: parseInt(element.css('height'))
				};

				angular.extend(opts, $parse(attrs.ngTrianglify)(scope));

				opts.width = opts.width * 2;
				opts.height = opts.height * 2;

				var pattern = Trianglify(opts);

				if (element.prop('tagName').toLowerCase() == 'img') {
					element.attr('src', pattern.png());
				} else {
					element.css({
						'background-image': 'url(' + pattern.png() + ')'
					});
				}
			});
		}
	};
}]);

alt.factory('status', ['$rootScope', '$filter', '$http', 'parse', 'url', function ($rootScope, $filter, $http, parse, url) {
	var addStatus = function (status, update) {
		var add = false;

		if (status == null) return false;

		if ( ! $rootScope.statuses) $rootScope.statuses = [];

		angular.forEach($rootScope.statuses, function (currentStatus, currentStatusKey) {
			if (currentStatus.statusId == status.statusId) {

				angular.forEach(status, function (statusItem, statusKey) {
					$rootScope.statuses[currentStatusKey][statusKey] = statusItem;
				});

				add = true;

				return add;
			}
		});

		if (add == false){
			if (update == true) {
				$rootScope.statuses.unshift(status);
			} else {
				$rootScope.statuses.push(status);
			}
		}

		$rootScope.statuses = $filter('orderBy')($rootScope.statuses, 'cTimestamp', true);
	};

	return {
		addStatus: addStatus,
		addStatuses: function (statuses) {
			if ( ! $rootScope.statuses) $rootScope.statuses = [];

			angular.forEach(statuses, function (status) {
				addStatus(status);
			});
		},
		readStatus: function (statusId) {
			return $http.get(url.siteUrl('api/status/readstatus'), {
				params: {
					statusId: statusId
				}
			});
		},
		readStatuses: function (config) {
			return $http.get(url.siteUrl('api/status/readstatuses'), {
				params: {
					config: config
				}
			});
		},
		createStatus: function (statusItem, config) {
			statusItem.cTimestamp = Math.round(Date.now() / 1000);

			return $http.post(url.siteUrl('api/status/createstatus'), {
				statusItem: statusItem,
				config: config
			});
		},
		updateStatus: function (statusId, statusItem, config) {
			statusItem.uTimestamp = Math.round(Date.now() / 1000);

			return $http.post(url.siteUrl('api/status/updatestatus'), {
				statusId: statusId,
				statusItem: statusItem,
				config: config
			});
		},
		parse: parse
	};
}]);

alt.factory('parse', ['$rootScope', '$window', 'url', function ($rootScope, $window, url) {
	return function (data) {
		if ($rootScope.authed == true && data.authed != true) angular.element('#signin-modal').modal({
			backdrop: 'static',
			keyboard: false
		}).modal('show').on('hide.bs.modal', function () {
			$window.location.reload();
		});

		if (data.authed == true) {
			$rootScope.authed = true;
		} else {
			$rootScope.authed = false;
		}

		return data.data;
	};
}]);

alt.factory('color', function () {
	var colorHueToRGB = function (v1, v2, h) {
		if (h < 0) h++;
		if (h > 1) h--;

		if ((6 * h) < 1) return v1 + (v2 - v1) * 6 * h;
		if ((2 * h) < 1) return v2;
		if ((3 * h) < 2) return v1 + (v2 - v1) * ((2 / 3) - h) * 6;

		return v1;
	};

	return {
		colorRandomHex: function () {
			var red = Math.floor((Math.random() * 255)).toString(16);
			var green = Math.floor((Math.random() * 255)).toString(16);
			var blue = Math.floor((Math.random() * 255)).toString(16);

			if (red.length == 1) red = red + red;
			if (green.length == 1) green = green + green;
			if (blue.length == 1) blue = blue + blue;

			return red.toUpperCase() + green.toUpperCase() + blue.toUpperCase();
		},
		colorHexToHSL: function (colorHex) {
			if (typeof colorHex === 'undefined') return null;

			colorHex = colorHex.replace('#', '');

			if (colorHex.length == 3) colorHex = colorHex.charAt(0) + colorHex.charAt(0) + colorHex.charAt(1) + colorHex.charAt(1) + colorHex.charAt(2) + colorHex.charAt(2);

			if (colorHex.length != 6) return null;

			var red = parseInt(colorHex.charAt(0) + colorHex.charAt(1), 16) / 255;
			var green = parseInt(colorHex.charAt(2) + colorHex.charAt(3), 16) / 255;
			var blue = parseInt(colorHex.charAt(4) + colorHex.charAt(5), 16) / 255;

			var min = Math.min(red, green, blue);
			var max = Math.max(red, green, blue);
			var delMax = max - min;

			var l = (max + min) / 2;

			if (delMax == 0) {
				var h = 0;
				var s = 0;
			} else {
				if (l < 0.5) {
					var s = delMax / (max + min);
				} else {
					var s = delMax / (2 - max - min);
				}

				delRed = (((max - red) / 6) + (delMax / 2)) / delMax;
				delGreen = (((max - green) / 6) + (delMax / 2)) / delMax;
				delBlue = (((max - blue) / 6) + (delMax / 2)) / delMax;

				if (red == max) {
					var h = delBlue - delGreen;
				} else if (green == max) {
					var h = (1 / 3) + delRed - delBlue;
				} else if (blue == max) {
					var h = (2 / 3) + delGreen - delRed;
				}

				if (h < 0) h++;
				if (h > 1) h--;
			}

			return {
				h: h * 360,
				s: s,
				l: l
			};
		},
		colorHSLToHex: function (h, s, l) {
			h = h / 360;

			if (s == 0) {
				var red = l * 255;
				var green = l * 255;
				var blue = l * 255;
			} else {
				if (l < 0.5) {
					var v2 = l * (1 + s);
				} else {
					var v2 = (l + s) - (s * l);
				}

				var v1 = 2 * l - v2;

				var red = Math.round(255 * colorHueToRGB(v1, v2, h + (1 / 3)));
				var green = Math.round(255 * colorHueToRGB(v1, v2, h));
				var blue = Math.round(255 * colorHueToRGB(v1, v2, h - (1 / 3)));
			}

			red = red.toString(16);
			green = green.toString(16);
			blue = blue.toString(16);

			if (red.length == 1) red = red + red;
			if (green.length == 1) green = green + green;
			if (blue.length == 1) blue = blue + blue;

			return red.toUpperCase() + green.toUpperCase() + blue.toUpperCase();
		},
		colorHueToRGB: function (v1, v2, h) {
			return colorHueToRGB(v1, v2, h);
		}
	};
});

alt.factory('url', ['$rootScope', function ($rootScope) {
	var slashItem = function (item) {
		return item.replace(/\/+$/g, '') + '/';
	};

	return {
		siteUrl: function (uri) {
			return slashItem($rootScope.alt.baseUrl) + uri;
		}
	};
}]);

alt.filter('timespan', function () {
	return function (seconds, units) {
		var time = Date.now() / 1000;

		if (time <= seconds) {
			seconds = 1;
		} else {
			seconds = time - seconds;
		}

		var output = [];

		var years = Math.floor(seconds / 31557600);

		if (years > 0) {
			var string = years + ' ';

			if (years > 1) {
				string = string + 'Years';
			} else {
				string = string + 'Year';
			}

			output.push(string);
		}

		seconds -= years * 31557600;

		var months = Math.floor(seconds / 2629743);

		if (output.length < units && (years > 0 || months > 0)) {
			if (months > 0) {
				var string = months + ' ';

				if (months > 1) {
					string = string + 'Months';
				} else {
					string = string + 'Month';
				}

				output.push(string);
			}

			seconds -= months * 2629743;
		}

		var weeks = Math.floor(seconds / 604800);

		if (output.length < units && (years > 0 || months > 0 || weeks > 0)) {
			if (weeks > 0) {
				var string = weeks + ' ';

				if (weeks > 1) {
					string = string + 'Weeks';
				} else {
					string = string + 'Week';
				}

				output.push(string);
			}

			seconds -= weeks * 604800;
		}

		var days = Math.floor(seconds / 86400);

		if (output.length < units && (years > 0 || months > 0 || weeks > 0 || days > 0)) {
			if (days > 0) {
				var string = days + ' ';

				if (days > 1) {
					string = string + 'Days';
				} else {
					string = string + 'Day';
				}

				output.push(string);
			}

			seconds -= days * 86400;
		}

		var hours = Math.floor(seconds / 3600);

		if (output.length < units && (years > 0 || months > 0 || weeks > 0 || days > 0 || hours > 0)) {
			if (hours > 0) {
				var string = hours + ' ';

				if (hours > 1) {
					string = string + 'Hours';
				} else {
					string = string + 'Hour';
				}

				output.push(string);
			}

			seconds -= hours * 3600;
		}

		var minutes = Math.floor(seconds / 60);

		if (output.length < units && (years > 0 || months > 0 || weeks > 0 || days > 0 || hours > 0 || minutes > 0)) {
			if (minutes > 0) {
				var string = minutes + ' ';

				if (minutes > 1) {
					string = string + 'Minutes';
				} else {
					string = string + 'Minute';
				}

				output.push(string);
			}

			seconds -= minutes * 60;
		}

		if (output.length == 0) {
			var string = seconds + ' ';

			if (seconds > 1) {
				string = string + 'Seconds';
			} else {
				string = string + 'Second';
			}
		}

		return output.join(', ');
	};
});

alt.filter('unique', function () {
	return function (items, key) {
		var output = [];
		var keys = [];

		angular.forEach(items, function (item) {
			if (keys.indexOf(item[key]) < 0) {
				output.push(item);

				keys.push(item[key]);
			}
		});

		return output;
	};
});