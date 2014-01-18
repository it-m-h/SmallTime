/*!
 * Small Time - JQuerry - Funktionen
 */
// Start - -------------------------------------------------------------
function init() {
        //document.getElementById("wrapper").style.display='none';
}
function opendetails(seite)
{
        //alert(seite)

        load(seite);
        //document.getElementById("details").style.display='block'
        openOffersDialog();

}

function closedetails()
{
        //document.getElementById("details").innerHTML = "";
        //document.getElementsByName("details").value = "";
        //document.getElementById("wrapper").style.display='none';
        //document.getElementsByName("boxpopup").innerHTML = "";
        closeOffersDialog('boxpopup');
}

// Seiten - Handling----------------------------------------------------

function load(seite) {
         $.ajax({
            url: './module/sites_admin/' + seite + ".php",
            type: 'post',
            dataType: 'text',
            async: true,
            success: function(response) {
                 document.getElementById("details").innerHTML = response;
            }
         });
        }
function load2(seite) {
         $.ajax({
            url: './module/sites_admin/' + seite + ".php",
            type: 'post',
            dataType: 'text',
            async: true,
            success: function(response) {
                 document.getElementById("details2").innerHTML = response;
            }
         });

        }
	
function openOffersDialog() {
        $('#overlay').fadeIn('fast', function() {
                $('#boxpopup').css('display','block');
        $('#boxpopup').animate({'left':'50%'},300);
    });
}


function closeOffersDialog(prospectElementID) {
        $(function($) {
                $(document).ready(function() {
                        $('#' + prospectElementID).css('position','absolute');
                        $('#' + prospectElementID).animate({'left':'-0%'}, 300, function() {
                                $('#' + prospectElementID).css('position','fixed');
                                $('#' + prospectElementID).css('left','350%');
                                $('#overlay').fadeOut('fast');
                        });
                });
        });
}

