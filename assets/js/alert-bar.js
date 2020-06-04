(function( $ ) {
	window.DBI_ALERT_BAR = {
		/**
		 * Init.
		 */
		init: function() {
			if ( !$( '.alert-bar' ).length ) {
				return;
			}

			DBI_ALERT_BAR.setup_alert_bars();
			DBI_ALERT_BAR.toggle_email_form();
		},

		/**
		 * Setup alert bars.
		 */
		setup_alert_bars: function() {
			var $alert_bars = $( '.alert-bar' );

			if ( $alert_bars.length <= 0 ) {
				return;
			}

			$( 'body' ).wrapInner( '<div class="js-body-wrap" />' );

			$alert_bars.each( function() {
				if ( $.cookie( 'dbrains-coupon' ) || $.cookie( 'dbrains-coupon-error' ) ) {
					return;
				}

				var alert_bar = $( this ).data( 'slug' );
				var $notice = $( this );

				if ( $notice[ 0 ] ) {
					$( '.dismiss', $notice ).click( function( e ) {
						e.preventDefault();
						if ( $( this ).parents( '.alert-bar' ).hasClass( 'alert-bar-narrow' ) ) {
							$( this ).parents( '.alert-bar' ).addClass( 'alert-bar--narrow' ).removeClass( 'alert-bar--show-form' );
						} else {
							$( this ).parents( '.alert-bar' ).hide();
						}
						$.cookie( alert_bar, 1, { expires: 365, path: '/' } );
					} );

					if ( !$.cookie( alert_bar ) ) {
						$( document ).ready( function() {
							setTimeout( function() {
								if ( $notice.hasClass( 'alert-bar-footer' ) ) {
									var $last_footer = $( 'footer' ).last();

									$notice.insertAfter( $last_footer ).show();

									if ( $notice.hasClass( 'alert-bar-sticky' ) ) {
										$( 'body' ).addClass( 'footer-sticky-alert-bar' ).css( 'padding-bottom', $notice.outerHeight() );
									}

								} else {
									$notice
										.insertBefore( $notice.closest( '.js-body-wrap' ) )
										.css( 'margin-top', -1 * $notice.outerHeight() )
										.show()
										.animate( { 'margin-top': 0 }, 800 );
								}

								var $timer = $notice.find( '.alert-bar__countdown' );

								if ( $timer.length > 0 ) {
									DBI_ALERT_BAR.setup_countdown( $timer );
								}
							}, 1000 );
						} );
					} else {
						if ( $notice.hasClass( 'alert-bar-narrow' ) ) {
							$notice.addClass( 'alert-bar--narrow' ).show();
						}
					}
				}
			} );
		},

		/**
		 * Toggle email subscribe form.
		 */
		toggle_email_form: function() {
			$( '.alert-bar .dbi-btn__toggle-form' ).on( 'click', function( e ) {
				e.preventDefault();

				var $alert_bar = $( this ).parents( '.alert-bar' );

				if ( $alert_bar.hasClass( 'alert-bar--narrow' ) ) {
					$alert_bar.removeClass( 'alert-bar--narrow' );

					$timer = $alert_bar.find( '.alert-bar__countdown' );

					if ( $timer.length > 0 ) {
						DBI_ALERT_BAR.setup_countdown( $timer );
					}

					return;
				}

				$alert_bar.addClass( 'alert-bar--show-form' ).find( 'input[type="email"]' ).focus();
			} );
		},

		/**
		 * Setup a countdown timer.
		 */
		setup_countdown: function( $timer ) {
			var date = $timer.data( 'timer-date' );
			var $days = $timer.find( '.days' );
			var $hours = $timer.find( '.hours' );
			var $minutes = $timer.find( '.minutes' );
			var $seconds = $timer.find( '.seconds' );

			DBI_ALERT_BAR.update_countdown( date, $days, $hours, $minutes, $seconds );

			var interval = setInterval( function() {
				var remaining = DBI_ALERT_BAR.update_countdown( date, $days, $hours, $minutes, $seconds );

				if ( remaining.total <= 0 ) {
					clearInterval( interval );
				}
			}, 1000 );
		},

		/**
		 * Update a countdown timer's elements.
		 */
		update_countdown: function( date, $days, $hours, $minutes, $seconds ) {
			var remaining = DBI_ALERT_BAR.calculate_time_remaining( date );

			$days.html( remaining.days );
			$hours.html( remaining.hours );
			$minutes.html( remaining.minutes );
			$seconds.html( remaining.seconds );

			return remaining;
		},

		/**
		 * Calculate the time remaining.
		 */
		calculate_time_remaining: function( end_time ) {
			end_time = end_time.replace(/ /g,"T"); // Fix for Safari not liking the space between date and time in the string
			var endDate = new Date( end_time );
			var nowDate = new Date();

			var time = endDate.getTime() - nowDate.getTime();
			var seconds = Math.floor( (time / 1000) % 60 );
			var minutes = Math.floor( (time / 1000 / 60) % 60 );
			var hours = Math.floor( (time / (1000 * 60 * 60)) % 24 );
			var days = Math.floor( time / (1000 * 60 * 60 * 24) );

			return {
				'total': time,
				'days': (days > 0) ? days : 0,
				'hours': (hours > 0) ? hours : 0,
				'minutes': (minutes > 0) ? minutes : 0,
				'seconds': (seconds >= 0) ? ('0' + seconds).slice( -2 ) : 0,
			};
		}
	};

	$( document ).ready( function() {
		DBI_ALERT_BAR.init();
	} );
})( jQuery );