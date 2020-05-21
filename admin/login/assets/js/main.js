
function slide()
{
	$('#theme').animate({right:0},{
	duration: 300,
	specialEasing: {
		height: "easeOutQuint"
	}});
}

$('#themeopen').click(function()
{
	slide();
});

$(document).ready(function()
{
	$('iframe').css('opacity','0');
	$('#load').fadeIn(10);
});

$('iframe').load(function()
{
	$('#load').css('display','none');
	
	$('iframe').animate({
		opacity: 1
	},{
		duration: 700
	});
});

function iframe(obj)
{
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
