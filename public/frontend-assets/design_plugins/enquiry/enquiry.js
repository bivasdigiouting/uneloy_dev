
// enquiry submission

$('#enquiryForm').validate({
	rules: {
		name: "required",
		cont: {
			required: true,
			minlength: 10,
		},
		mail: {
			required: true,
			email: true
		}
	},
	messages: {
		name: "Please enter your Name.",
		cont: {
			required: "Please enter your Mobile No.",
			minlength: "Mobile no. must be 10 digits long."
		},
		mail: {
			required: "Please enter your Email ID.",
			email: "Please enter a valid Email ID."
		}
	},
	submitHandler: function () {
		$('body').append(loader);
		$.ajax({
			url: 'https://blog.versatileitsolution.com/versatile/Api/sendMail.php',
			type: 'post',
			dataType: 'json',
			data: {
				"name": $('#name').val(),
				"add": $('#add').val(),
				"cont": $('#cont').val(),
				"mail": $('#mail').val(),
				"message": $('#message').val(),
				//important
				"website": "www.uonely.com",
				"mailTo": "info@uonely.com"
			},
			success: function (response) {
				var msg;
				$('.loader').remove();

				if (response.success == true) {
					msg += '<div class="msg success wow fadeInUp  animated">';
					msg += '<span>' + response.message + '</span>';
					msg += '<a onclick="return DelMsg(this)" ID="DelMsg">&times;</a></div>'
				} else if (response.success == false) {
					msg += '<div class="msg error wow fadeInUp  animated">';
					msg += '<span>' + response.message + '</span>';
					msg += '<a onclick="return DelMsg(this)" ID="DelMsg">&times;</a></div>'
				}

				$('body').append(msg);
				setTimeout(function () { DelMsg('#DelMsg'); }, 4000);
				$('form')[0].reset();
			}
		});
	}
});

// loader & message 

var loader = '<div class="loader" ><div class="loader-msg" >';
loader += '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>Please wait . . .';
loader += '</div></div>';

// message remove 

function DelMsg(btn) {
	$(btn).parents('.msg').removeClass('fadeInUp').addClass('fadeOutUp');
	setTimeout(function () {
		$(btn).parents('.msg').remove();
	}, 2000);
}

// type only numer 

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}

