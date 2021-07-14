function tisAlertMessage(msgTitle,msgContant,msgType,msgSize,urlRedirect,IsRedirect){
  var Alertclass="alertError bootbox-alert";

  if(msgType!=""){
    if(msgType=="error"){
        Alertclass="alertError bootbox-alert";
        bootbox.alert({
          title:msgTitle,
          size: msgSize,
          closeButton: false,
          animate: true,
          message: msgContant,
          centerVertical:true,
          className: Alertclass,
          buttons: {
              ok: {
                  label: '<i class="far fa-thumbs-up"></i> ตกลง',
                  className: 'btn-info btn-sm'
              }
          }
        });
    }else if(msgType=="completed"){
      Alertclass="alertComplete bootbox-alert";
      bootbox.alert({
        title:msgTitle,
        size: msgSize,
        closeButton: false,
        animate: true,
        message: msgContant,
        centerVertical:true,
        className: Alertclass,
        buttons: {
            ok: {
                label: '<i class="far fa-thumbs-up"></i> ตกลง',
                className: 'btn-info btn-sm'
            }
        },
        callback: function(){
          if(IsRedirect){
            location.href=urlRedirect;
          }else{
            if(urlRedirect!=''){
              location.reload();
            }

          }
          //
        }

    });
      return;
    }else if(msgType=="info"){
      Alertclass="alertInfo bootbox-alert";
      bootbox.alert({
        title:msgTitle,
        size: msgSize,
        closeButton: false,
        animate: true,
        message: msgContant,
        centerVertical:true,
        className: Alertclass,
        buttons: {
            ok: {
                label: '<i class="far fa-thumbs-up"></i> ตกลง',
                className: 'btn-info btn-sm'
            }
        }
      });
    }

  }
//msgSize   Small	'small', 'sm'   Large	'large', 'lg' Extra large	'extra-large', 'xl'

}
