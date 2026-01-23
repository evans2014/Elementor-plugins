      jQuery(document).ready(function($) {
        let currentTech = '';
        let currentPage = 1;

        function showLoader(show = true) {
          if (show) {
            $('#job-loader').show();
          } else {
            $('#job-loader').hide();
          }
        }
		let shouldScroll = false;

		$(document).on('click', '.ajax-page-link', function (e) {
		  e.preventDefault();
		  shouldScroll = true;
		  currentPage = $(this).data('page');
		  loadJobs(currentTech, currentPage);
		});

        function loadJobs(tech = '', page = 1) {
          showLoader(true);

          $.ajax({
            url: jobs_ajax.ajax_url,
            type: 'POST',
            data: {
              action: 'filter_jobs',
              technology: tech,
              page: page,
            },
            success: function(res) {
              $('#jobs-list').html(res.jobs_html);
              $('#pagination-container').html(res.pagination_html);
			  if (shouldScroll) {
					$('html, body').animate({
					  scrollTop: $('.filter-top').offset().top - 220
					}, 600);
					shouldScroll = false;
				  }

              showLoader(false);
            },
            error: function() {
              alert('Error loading jobs.');
              showLoader(false);
            }
          });
        }

        $('#job-technology').on('change', function () {
          currentTech = $(this).val();
          currentPage = 1;
          loadJobs(currentTech, currentPage);
        });

        $(document).on('click', '.ajax-page-link', function (e) {
          e.preventDefault();
          currentPage = $(this).data('page');
          loadJobs(currentTech, currentPage);
        });

        loadJobs();
            
      });
