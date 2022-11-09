/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
jQuery( "#acs-d1,#acs-d2" ).datepicker({
                           dateFormat: 'yy-mm-dd',
                            beforeShow: function(input, inst) {
      
       jQuery('#ui-datepicker-div').addClass('acs-date-picker');
   }
                           //minDate: new Date,
                           //changeMonth:true,
                          // changeYear:true,
 });
 });