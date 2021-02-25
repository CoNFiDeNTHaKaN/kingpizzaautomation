function addHelpers() {
	Number.prototype.militaryToFriendly = function() {
		var military = this;
		var suffix = 'am';
		if (military > 1159) {
			var suffix = 'pm';
			military = military - 1200;
		}
		var time = military.toString().split('');
		console.log(time)
		time.splice(-2, 0, '.');
		time = time.join('')
		return (time + suffix);
	}

	String.prototype.militaryToFriendly = function() {
		return parseInt(this).militaryToFriendly();
	}

	String.prototype.replaceAll = function(find, replace) {
		return this.replace(new RegExp(find, 'g'), replace);
	}
}

export default addHelpers();