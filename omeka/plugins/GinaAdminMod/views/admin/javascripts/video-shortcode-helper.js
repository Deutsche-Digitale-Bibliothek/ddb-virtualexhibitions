jQuery(document).ready(function($) {

    var videoShortcodeDialog, videoShortcodeFrom,
        videoShortcodeType = $("#video-shortcode-type"),
        videoShortcodeId = $("#video-shortcode-id"),
        videoShortcodeStart = $("#video-shortcode-start"),
        videoShortcodeStop = $("#video-shortcode-stop"),
        allFields = $([]).add(videoShortcodeType).add(videoShortcodeId).add(videoShortcodeStart).add(videoShortcodeStop),
        tips = $(".validateTips");

    function updateTips(t) {
        tips
          .text(t)
          .addClass("ui-state-error");

        setTimeout(function() {
          tips.removeClass("ui-state-error", 1500);
        }, 4500);
    }

    function checkRegexp(o, regexp, n) {
        if (!(regexp.test(o.val()))) {
          o.addClass("ui-state-error");
          updateTips(n);
          return false;
        } else {
          return true;
        }
    }

    function populateDialog() {
        $('#video-shortcode-dialog-form .validateTips').text('');
        var shortcode = $('#Elements-74-0-text').val();
        var baseParts = shortcode.match(/(\[\[)([^\]\:]+):([^\]]+)(\]\])/i);
        if (baseParts !== null && 3 in baseParts) {
            var videoParts = baseParts[3].match(/(ddb|vimeo):([a-zA-Z0-9]*)(.*)/i);
            if (videoParts !== null && 1 in videoParts) {
                $('#video-shortcode-type').val(videoParts[1]);
            }
            if (videoParts !== null && 2 in videoParts) {
                $('#video-shortcode-id').val(videoParts[2]);
            }
            if (videoParts !== null && 3 in videoParts) {
                var timecodeParts = videoParts[3].match(/(-t=)([0-9]*)([\-]*)([0-9]*)/i)
                if (timecodeParts !== null && 2 in timecodeParts) {
                    $('#video-shortcode-start').val(timecodeParts[2]);
                }
                if (timecodeParts !== null && 4 in timecodeParts) {
                    $('#video-shortcode-stop').val(timecodeParts[4]);
                }
            }
        }
    }

    function commitShortcode() {
        var valid = true;
        allFields.removeClass("ui-state-error");
        valid = valid && checkRegexp(videoShortcodeType, /^ddb|vimeo$/i, "Wählen Sie einen Video-Typ aus!");
        valid = valid && checkRegexp(videoShortcodeId, /^[a-zA-Z0-9]+$/i, "Bei der ID sind nur alphanummerische Zeichen erlaubt!");
        valid = valid && checkRegexp(videoShortcodeStart, /^[0-9]*$/i, "Bei der Startzeit sind nur Zahlen erlaubt!");
        valid = valid && checkRegexp(videoShortcodeStop, /^[0-9]*$/i, "Bei der Stoptzeit sind nur Zahlen erlaubt!");
        if (valid) {
            var updateValue = '[[video:' + videoShortcodeType.val() + ':' + videoShortcodeId.val();
            var videoShortcodeStartVal = videoShortcodeStart.val();
            var videoShortcodeStopVal = videoShortcodeStop.val();
            if (videoShortcodeStartVal !== '' && videoShortcodeStartVal !== null) {
                updateValue = updateValue + '-t=' + videoShortcodeStartVal;
                if (videoShortcodeStopVal !== '' && videoShortcodeStopVal !== null) {
                    updateValue = updateValue + '-' + videoShortcodeStopVal;
                }
            }
            updateValue = updateValue + ']]';

            $('#Elements-74-0-text').val(updateValue);
            videoShortcodeDialog.dialog("close");
        }
        return valid;
    }

    videoShortcodeDialog = $("#video-shortcode-dialog-form").dialog({
        autoOpen: false,
        height: 450,
        width: 350,
        modal: true,
        buttons: {
            "Übernehmen": commitShortcode,
            "Abbrechen": function() {
                videoShortcodeDialog.dialog("close");
            }
        },
        close: function() {
            videoShortcodeFrom[0].reset();
            allFields.removeClass("ui-state-error");
        }
    });

    videoShortcodeFrom = videoShortcodeDialog.find("form").on("submit", function(event) {
        event.preventDefault();
        commitShortcode();
    });

    setTimeout(function() {
        $('#element-74 .columns:first-child').append('<button class=\"green\" id=\"video-shortcode-helper\">Video Shortcode Helfer</button>');
        $('#video-shortcode-helper').click(function(e) {
            e.preventDefault();
            populateDialog();
            videoShortcodeDialog.dialog("open");
        });
    }, 1000);


});