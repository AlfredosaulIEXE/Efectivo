$(function(){
	
	$('#modalButton').click(function(){
		$('#modal').modal('show')
		.find('#modalContent')
		 .load($(this).attr('value'));
		
		
	});
	
});
$(function(){
	
	$('#modalTaskButton').click(function(){
		$('#taskmodal').modal('show')
		.find('#modalContent')
		 .load($(this).attr('value'));
		
		
	});
	
});
$(function(){
	
	$('#modaldefectButton').click(function(){
		$('#defectmodal').modal('show')
		.find('#modalContent')
		 .load($(this).attr('value'));
		
		
	});
	
});
// function checkout time activity from user
function timeActivity()
{
    setTimeout(function(){
        // let timerInterval;
        // const swalWithBootstrapButtons = Swal.mixin({
        //     customClass: {
        //         confirmButton: 'btn btn-success',
        //         cancelButton: 'btn btn-danger'
        //     },
        //     buttonsStyling: false
        // });
        //
        // swalWithBootstrapButtons.fire({
        //     title: 'Tiempo de actividad de sesión',
        //     animation: false,
        //     customClass : {
        //       popup: 'animated tada'
        //     },
        //     html: 'Se cerrará su sesión en : <strong></strong> segundos. ',
        //     text: "You won't be able to revert this!<strong></strong>",
        //     type: 'warning',
        //     timer: 60000,
        //     width: 600,
        //     confirmButtonText: 'Seguir en actividad',
        //     reverseButtons: true,
        //     onBeforeOpen: () => {
        //         timerInterval = setInterval(() => {
        //             Swal.getContent().querySelector('strong')
        //                 .textContent = (Swal.getTimerLeft() / 1000).toFixed(0) + ":"
        //         }, 100)
        //     },
        //     onClose: () => {
        //         clearInterval(timerInterval)
        //     }
        // }).then((result) => {
        //     console.log(result.dismiss);
        //     if (result.value || result.dismiss === Swal.DismissReason.backdrop) {
        //         swalWithBootstrapButtons.fire(
        //             'Se ha mantenido la sesión abierta!',
        //             'success'
        //         );
        //         timeActivity();
        //     } else if (
        //         /* Read more about handling dismissals below */
        //          result.dismiss === Swal.DismissReason.timer
        //     ) {
        //         // swalWithBootstrapButtons.fire(
        //         //     'Cancelled',
        //         //     'Your imaginary file is safe :)',
        //         //     'error'
        //         // );
        //         timeActivity();
        //         location.reload();
        //         // window.location.href = "/livecrm/web/index.php?r=site/useractivity";
        //
        //     }
        // });

		$.ajax(
			{
				url:'http://crmoperacionesfinancieras.com/livecrm/web/index.php?r=site/useractivity',
				success: function (result) {
					console.log('actualización de tiempo');
                },
				error: function () {
					alert('Error')
                }
			}
		);
         timeActivity();
    }, (5 * 60 * 1000));


}

(function () {
	console.log('first_activity');
    $.ajax(
        {
            url:'http://crmoperacionesfinancieras.com/livecrm/web/index.php?r=site/useractivity',
            success: function (result) {
                console.log('actualización de tiempo');
            },
            error: function () {
                alert('Error')
            }
        }
    );
    timeActivity();
})();
