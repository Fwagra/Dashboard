{!! Form::open(['action' => ['ProjectController@searchProject'], 'method' => 'GET', 'class' => "form-search"]) !!}
    {!! Form::text('project', '', ['id' =>  'project', 'placeholder' =>  trans('project.search_placeholder')])!!}
    {!! Form::submit(trans('project.search_button'), array('class' => 'button expand')) !!}
{!! Form::close() !!}

<script>
	jQuery(document).ready(function($) {
		$( "#project" ).autocomplete({
		  source: "{!! route('project.search') !!}",
		  minLength: 2,
		  response: function( event, ui ) {
		  },
		  select: function(event, ui) {
		  	$('#project').val(ui.item.name);
		  	$('#project').data('slug', ui.item.slug);
		  }
		});
		$(".form-search").on('submit', function(event) {
			event.preventDefault();
			if($('#project').data('slug')){
				window.location.href = "{!! route('project.index') !!}" + '/' + $('#project').data('slug');
			}
		});
	});
</script>