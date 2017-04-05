jQuery(document).ready(function() {
    //change costume inputs 
    var img = jQuery('.images');
    var custom_name = jQuery('.name-on-tshirt');
    var custom_number = jQuery('.number-on-tshirt');
    var name_val = custom_name.val();
    //console.log(name_val);
    //if(name){
    custom_name.keyup(function() {
        var name_val = custom_name.val();
        var img_newwrap = jQuery('.woocommerce-main-image');
        var checkname = jQuery('.nameval');
        console.log(checkname);
        if (checkname.length) {
            checkname.hide();
        }
        img_newwrap.append('<div class="nameval">' + name_val + '</div>');
    });
    //}
    //change number in image
    //
    if (custom_number.length) {
        custom_number.keyup(function() {
            var custom_number_val = custom_number.val();
            //console.log(custom_number_val);
            if (isNaN(custom_number_val) || custom_number_val.length > 2) {
                alert("Only  two digit number allowed");
                custom_number.val('');
                return;
            }
            var num_newwrap = jQuery('.woocommerce-main-image');
            var checknum = jQuery('.numval');
            //console.log(checknum);
            if (checknum.length) {
                checknum.hide();
            }
            num_newwrap.append('<div class="numval">' + custom_number_val + '</div>');
        });
    }
    jQuery('.fontcolor-on-tshirt').change(function() {
        var color = jQuery('.fontcolor-on-tshirt').css('background-color');
        jQuery('.fontcolor-on-tshirt').css('color', color);
        //console.log(color);
        //jQuery('.nameval').hide();
        jQuery('.nameval').css("color", color);
        jQuery('.numval').css("color", color);
    });
    // 	function update(jscolor) {
    //     // 'jscolor' instance can be used as a string
    //     //document.getElementById('rect').style.backgroundColor = '#' + jscolor
    //     jQuery('.nameval').css('color',color);
    // 	jQuery('.namval').css('color',color);
    // }
    //change into image 
    window.takeScreenShot = function() {
        html2canvas(document.getElementsByClassName("images"), {
            onrendered: function(canvas) {
                //       var img = canvas.toDataURL()
                // window.open(img);
                // var imagedata = canvas.toDataURL('image/png');
                var imagedata = canvas.toDataURL('image/png');
                img.width = 500;
                img.height = 500;
                var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
                // var a = document.createElement('a');
                //   // toDataURL defaults to png, so we need to request a jpeg, then convert for file download.
                //   a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                //   a.download = 'somefilename.jpg';
                //console.log(a);
                jQuery.ajax({
                    url: 'http://localhost/surya/demo/wp-admin/admin-ajax.php',
                    data: {
                        action: "save_edited_data",
                        imgdata: imgdata
                    },
                    type: 'post',
                    success: function(response) {
                        console.log(response);
                        jQuery('.img-on-tshirt').val('<img width="100" height="100" src="' + response + '">');
                        jQuery('.img-on-tshirt-show').html('<img  width="100" height="100"  src="' + response + '">');
                    }
                });
            }
        });
    }
});