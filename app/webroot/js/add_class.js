	/* 
	
		MAIN CODE FOR SCHEDULR ADD CLASS PAGE
	
	*/

	// indexOf support for IE (from MDC) 
	if (!Array.prototype.indexOf) 
	{ 
	    Array.prototype.indexOf = function(elt /*, from*/)    
	    { 
	        var len = this.length >>> 0; 
	 
	        var from = Number(arguments[1]) || 0; 
	        from = (from < 0) ? Math.ceil(from) : Math.floor(from); 
	        if (from < 0) 
	            from += len; 
	 
	        for (; from < len; from++) 
	        { 
	            if (from in this && this[from] === elt)   
	                return from; 
	        } 
	        return -1; 
	    }; 
	} 
	
	// Strip tags function for jQuery
	jQuery.fn.stripTags = function() { return this.replaceWith( this.html().replace(/<\/?[^>]+>/gi, '') ); };	
 
	
	// Contains 10 different colors used for each course
	var color_array = [
						"#8ADCFF", 
						"#FFDD75", 
						"#A6CAA9",
						"#CD85FE", 
						"#DEB19E",
						"#8DC7BB",
						"#E29BFD", 
						"#B89AFE", 
						"#F7DE00", 
						"#FFFFFF"
					  ];
	var color_index = 0;	
	var DELETE_CLASS_ID = -1;
	/*
		Event responders
	*/
	$('.day_buttons li').live('click', function() {
		if($(this).hasClass('enabled')) {
			$(this).removeClass('enabled');
		} else {
			$(this).addClass('enabled');
		}
	});
	
	$('.popup #CourseStartTimeMeridian').live('change', function() {
		if($(this).val() == 'pm') $('.popup #CourseEndTimeMeridian').val('pm');
	});

	

	$('#addClass').live('click', function() {
		var tmp, courseName, courseCredits, startHour, startMinute, startMeridian, startMinutes, 
			endHour, endMinute, endMeridian, endMinutes, height, top, day, hasError = false;
		courseName = $('.popup #CourseName').val().replace(/<\/?[^>]+>/gi, '');
		courseCredits = $('.popup #CourseCredits').val();
				
		if($.trim(courseName) == '') {
			$('.popup #CourseName').parent().find('.error_msg').html("You must enter a course name");
			hasError = true;
		} else {
			$('.popup #CourseName').parent().find('.error_msg').html("");
		}
		
		
		startHour = parseInt($('.popup #CourseStartTimeHour').val()) 
			|| parseInt($('.popup #CourseStartTimeHour').val().charAt(1));
		startMinute = parseInt($('.popup #CourseStartTimeMin').val()) 
			|| parseInt($('.popup #CourseStartTimeMin').val().charAt(1));
		startMeridian = $('.popup #CourseStartTimeMeridian').val();
		if(startMeridian == 'am' && startHour == 12) startHour = 0;
		else if(startMeridian == 'pm' && startHour == 12) startHour = 12;	
		else if(startMeridian == 'pm') startHour += 12;	
		startMinutes = (startHour * 60) + startMinute - TIME_DELTA;

		endHour = parseInt($('.popup #CourseEndTimeHour').val()) 
			|| parseInt($('.popup #CourseEndTimeHour').val().charAt(1));
		endMinute = parseInt($('.popup #CourseEndTimeMin').val()) 
			|| parseInt($('.popup #CourseEndTimeMin').val().charAt(1));
		endMeridian = $('.popup #CourseEndTimeMeridian').val();
		if(endMeridian == 'am' && endHour == 12) endHour = 0;
		else if(endMeridian == 'pm' && endHour == 12) endHour = 12;
		else if(endMeridian == 'pm') endHour += 12;
		endMinutes = (endHour * 60) + endMinute - TIME_DELTA;
	
		if(startMinutes >= endMinutes) {
			$('.popup #CourseEndTimeMin').parent().find('.error_msg').html("Must be after start time");
			hasError = true;
		} else if(endMinutes < 1) {
			$('.popup #CourseEndTimeMin').parent().find('.error_msg').html("Must be after 7:00am");
			hasError = true;
		} else if(endMinutes > 1019) {
			$('.popup #CourseEndTimeMin').parent().find('.error_msg').html("Must be before 12:00am");
			hasError = true;			
		} else {
			$('.popup #CourseEndTimeMin').parent().find('.error_msg').html("");
		}
		
		
		if(startMinutes < 0) {
			$('.popup #CourseStartTimeMin').parent().find('.error_msg').html("Must be after 6:59am");
			hasError = true;
		} else if(startMinutes > 1018) {
			$('.popup #CourseStartTimeMin').parent().find('.error_msg').html("Must be before 11:59pm");
			hasError = true;
		} else {
			$('.popup #CourseStartTimeMin').parent().find('.error_msg').html("");
		}
		
		if(hasError) return;
		
		// Passed all validation. Continue...
		
		// If we are editing a class, we want to delete the existing class so this can replace it
		if(DELETE_CLASS_ID != -1) {
			deleteClass(DELETE_CLASS_ID);
		}
	
		height = (endMinutes - startMinutes) * MINUTE_HEIGHT + 1;
		top = TOP_DELTA + (startMinutes * MINUTE_HEIGHT) + (startHour - 8);
	
		var start_time = ((startHour > 12) ? (startHour - 12) : startHour) + ':' 
			+ ((startMinute < 10) ? '0' + startMinute : startMinute) + startMeridian;
		var end_time = ((endHour > 12) ? (endHour - 12) : endHour) + ':' 
			+ ((endMinute < 10) ? '0' + endMinute : endMinute) + endMeridian;

		var color = color_array[color_index];
		color_index++;	
		if(color_index >= 10) {
			color_index = 0;
		}
		c1 = new Course(height, top, day, courseName, courseCredits, start_time, end_time, color);

		$('.day_buttons li.enabled').each(function() {
			day = $(this).attr('id');
			c1.addDay(day);
		});
		c1.mySelector = $('.course_' + course_id);
		course_id++;
		c1.enable();
		all_classes.push(c1);
		resetCourseAddForm();
		$(document).trigger('close.facebox');
		$('#class_listing p#enable_info').show();
	});

	$('.class_item').live('click', function() {
		var courseObj = null;
		courseID = $(this).attr('id').substring(14);
		for (var c in all_classes) {
			if (parseInt(all_classes[c].myCourseId) == parseInt(courseID)) {
				courseObj = all_classes[c];
				break;
			}
		}
		if(courseObj == null) {
			// This happens in the case of deleting a class
			return;
			//alert('Something happened that shouldn\'t. Crap');
		}
	
		if($(this).hasClass('enabled')) {
			$(this).removeClass('enabled');
			$(this).addClass('disabled');		
			courseObj.disable();
		} else if($(this).hasClass('conflicted')) {
			alert('This currently conflicts with an enabled class. Try disabling another class first');
		} else if($(this).hasClass('disabled')) {
			$(this).removeClass('disabled');				
			$(this).addClass('enabled');
			courseObj.enable();
		}
	});
	
	
	$('#saveScheduleFinal').live('click', function() {
		if(disp_classes.length == 0) {
			alert('You don\'t have any enabled classes to save');
			$(document).trigger('close.facebox');
			return;
		}		
		$(document).trigger('close.facebox');
		$('#all_content').fadeTo('slow', .2);
		$('#loader').fadeIn('slow');
		var converted = Array();
		for(var c in disp_classes) {
			disp_classes[c].mySelector = null;
			converted.push(JSON.stringify(disp_classes[c]));
		}
		var title = $('.popup #scheduleTitle').val();
		var total_credits = $('#total_credits').text();
		$.post('/save/', 
			{'title': title, 'total_credits': total_credits, 'classes[]': converted},
			function(data) {
				window.location.href = "/static/" + data;
			}
		)
	});
	
	$('a.delete_class').live('click', function() {
		var class_id = $(this).parent().parent().attr('id').substring(14);
		deleteClass(class_id);
		return false;
	});
	
	$('.class').live('click', function(){
		my_class = null;
		var class_id = $(this).attr('class').substring($(this).attr('class').indexOf('_') + 1);
		for(var c in all_classes) {
			if(all_classes[c].myCourseId == class_id) {
				my_class = all_classes[c];
				break;
			}
		}
		if(my_class == null) return;	
		
		st = my_class.myStartTime;
		et = my_class.myEndTime;
		
		start_hour = st.substring(0, st.indexOf(':'));
		start_minute = st.substring(st.indexOf(':') + 1, st.length - 2);
		start_meridian = st.substring(st.length - 2);

		end_hour = et.substring(0, et.indexOf(':'));
		end_minute = et.substring(et.indexOf(':') + 1, et.length - 2);
		end_meridian = et.substring(et.length - 2);

		if(parseInt(end_hour) < 10) {
			end_hour = '0' + end_hour;
		}
		if(parseInt(end_minute) < 10 && parseInt(end_minute) > 0) {
			end_minute = '0' + end_minute;
		}
		if(parseInt(start_hour) < 10) {
			start_hour = '0' + start_hour;
		}
		if(parseInt(start_minute) < 10 && parseInt(start_minute) > 0) {
			start_minute = '0' + start_minute;
		}				
			
		jQuery.facebox({ div: '#addClassWindow' });
		$('.popup #CourseName').val(my_class.myCourseName);
		$('.popup #CourseCredits').val(my_class.myCourseCredits);
		$('.popup #CourseStartTimeHour').val(start_hour);
		$('.popup #CourseStartTimeMin').val(start_minute);
		$('.popup #CourseStartTimeMeridian').val(start_meridian);
		$('.popup #CourseEndTimeHour').val(end_hour);
		$('.popup #CourseEndTimeMin').val(end_minute);
		$('.popup #CourseEndTimeMeridian').val(end_meridian);
		
		for(var i = 0; i < my_class.myDayString.length; i++) {
			var let = my_class.myDayString.charAt(i);
			switch(let) {
				case 'M': $('.popup #monday').addClass('enabled'); break;
				case 'T': $('.popup #tuesday').addClass('enabled'); break;
				case 'W': $('.popup #wednesday').addClass('enabled'); break;
				case 'R': $('.popup #thursday').addClass('enabled'); break;
				case 'F': $('.popup #friday').addClass('enabled'); break;
				case 'S': $('.popup #saturday').addClass('enabled'); break;
				case 'U': $('.popup #sunday').addClass('enabled'); break;
			}
		}
		
		DELETE_CLASS_ID = class_id;
	});

	/*
		Course Object
	*/

	function Course(height, top, day, course_name, course_credits, start_time, end_time, color) {
		this.myHeight = height;
		this.myTop = top;
		this.myDays = Array();
		this.myCourseName = course_name;
		this.myCourseCredits = course_credits;
		this.myCourseId = course_id;
		this.addedToCourseList = false;
		this.myStartTime = start_time;
		this.myEndTime = end_time;
		this.myDayString = '';
		this.myColor = color;
	}

	Course.prototype.addDay = function(day) {
		this.myDays.push(day);
		div = '<div class = "class ' + day + ' course_' + course_id + '" style = "top: ' 
			+ this.myTop + 'px; height: ' + this.myHeight + 'px; background-color: ' + this.myColor + '; display:none;">';
		div += "<p>" + this.myCourseName + "<br />" + this.myCourseCredits + " Credits<br /> " 
			+ this.myStartTime + " - " + this.myEndTime + "</p>";
		div += "</div>";	
		$('#classes').append(div);	
		switch(day) {
			case 'monday': this.myDayString += 'M'; break;
			case 'tuesday': this.myDayString += 'T'; break;
			case 'wednesday': this.myDayString += 'W'; break;
			case 'thursday': this.myDayString += 'R'; break;
			case 'friday': this.myDayString += 'F'; break;
			case 'saturday': this.myDayString += 'S'; break;
			case 'sunday': this.myDayString += 'U'; break;
		}	
	}

	Course.prototype.enable = function() {
	
		// Check for conflicts
		if(!checkForConflicts(this)) {
			this.mySelector.fadeIn(400);
			// Add to course listing - without conflicts
			if(!this.addedToCourseList) {
				addToCourseList(this, false);
				this.addedToCourseList = true;
			}
			totalCredits += parseInt(this.myCourseCredits);		
			disp_classes.push(this);
			updateCourseList();
		} else {
			// Add to course listing as conflicting with current class
			if(!this.addedToCourseList) {
				addToCourseList(this, true);
				this.addedToCourseList = true;
			}
		}

		updateCreditTotals();
	}
	
	Course.prototype.disable = function() {
		for (var c in disp_classes) {
			if(disp_classes[c].myHeight == this.myHeight && disp_classes[c].myTop == this.myTop 
				&& disp_classes[c].myDay == this.myDay) {
				tmp = disp_classes.splice(c, 1);
				this.mySelector.fadeOut(200);
				break;
			}
		}
	
		totalCredits -= parseInt(this.myCourseCredits);
		updateCreditTotals();
		updateCourseList();
	}

	/*
		Additional functions for Courses and other stuff...
	*/
	function checkForConflicts(cse) {
		var thisHeight, thisTop, thisDays, sum;
		for (var c in disp_classes) {
			if(cse.myCourseId != disp_classes[c].myCourseId) {
				thisSum = disp_classes[c].myHeight + disp_classes[c].myTop;
				thisTop = disp_classes[c].myTop;
				thisDays = disp_classes[c].myDays;
				sum = cse.myHeight + cse.myTop;
				if((cse.myTop == thisTop && sum < thisSum) ||
					(cse.myTop == thisTop && sum == thisSum) ||
					(cse.myTop < thisTop && sum < thisSum && sum > thisTop) ||
					(cse.myTop < thisTop && sum > thisSum) ||
					(cse.myTop < thisTop && sum == thisSum) ||
					(cse.myTop > thisTop && cse.myTop < thisSum && sum > thisSum) ||
					(cse.myTop > thisTop && sum < thisSum) ||
					(cse.myTop > thisTop && sum == thisSum)
				)
				{
					for (var i in thisDays) {
						if(cse.myDays.indexOf(thisDays[i]) != -1) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	function updateCourseList() {
		// For every course in the list, update whether it's currently conflicted/unable to enable
		for (var c in all_classes) {
			var my_id = 'adjust_course_' + all_classes[c].myCourseId;
			if(!$('#' + my_id).hasClass('enabled') && checkForConflicts(all_classes[c])) {
				$('#' + my_id).removeClass('enabled');
				$('#' + my_id).removeClass('disabled');
				$('#' + my_id).addClass('conflicted');
			} else {
				if($('#' + my_id).hasClass('conflicted')) {
					$('#' + my_id).removeClass('conflicted');
					$('#' + my_id).addClass('disabled');
				}
			}
		}
	}	

	function addToCourseList(cse, hasConflict) {
		div = '<div class = "class_item '
		if(hasConflict) {
			div += 'conflicted';
		} else {
			div += 'enabled';
		}
		var my_id = 'adjust_course_' + cse.myCourseId;
		div += '" id = "' + my_id + '" style = "display:none">';
		div += '<p>' + cse.myCourseName;
		div += '<br />' + cse.myStartTime + ' - ' + cse.myEndTime + ' ' + cse.myDayString;
		div += '<br /><a href = "#" class = "delete_class">Delete Class</a>';
		div += '</p>';
		div += '</div>';
		//$('#class_listing').append(div);
		$(div).insertAfter('#class_listing h1')
		$('#' + my_id).fadeIn(500);
	}

	function updateCreditTotals() {
		$('#total_credits').text(totalCredits);
	}

	function resetCourseAddForm() {
		// Reset all inputs
		$('#CourseName').val('');
		$('#CourseCredits').val(3);
		$('input[type=checkbox]').each(function() {
			$(this).attr('checked', false);
		});	
		$('.popup #CourseStartTimeHour').val('07');
		$('.popup #CourseStartTimeMin').val('00');
		$('.popup #CourseStartTimeMeridian').val('am');
		$('.popup #CourseEndTimeHour').val('07');
		$('.popup #CourseEndTimeMin').val('00');
		$('.popup #CourseEndTimeMeridian').val('am');
	}
	
	function deleteClass(class_id) {
		for(var c in all_classes) {
			if(all_classes[c].myCourseId == class_id) {
				all_classes[c].mySelector.fadeOut(200);
				all_classes[c].mySelector.remove();
				tmp = all_classes.splice(c, 1);
			}
		}
		for(var c in disp_classes) {
			if(disp_classes[c].myCourseId == class_id) {
				var new_credits = parseInt($('#total_credits').html()) - parseInt(disp_classes[c].myCourseCredits);
				totalCredits = new_credits;
				$('#total_credits').html(new_credits);
				tmp = disp_classes.splice(c, 1);
				break;
			}
		}
		$('#adjust_course_' + class_id).remove();
	}