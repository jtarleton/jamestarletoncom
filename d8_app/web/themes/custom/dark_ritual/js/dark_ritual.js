Drupal.behaviors.dark_ritual = {
			attach: function() {

				jQuery('#marathon-table').DataTable({
"pageLength": 25,
"aaSorting": [ [6,'asc'] ]});

			console.log('dark_ritual');
			}
		}


/* Drupal.attachBehaviors(); */
