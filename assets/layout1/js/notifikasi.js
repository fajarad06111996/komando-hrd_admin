  function errordatabase(){
    // new Noty({
    //     theme: ' alert alert-danger alert-styled-left p-0',
    //     text: 'Error Get Database.',
    //     type: 'error',
    //     progressBar: true,
    //     timeout: 2500,
    //     closeWith: ['button']
    // }).show();
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Error Get Database.',
    })
  }

  function notifsukses(data,layout){
    var setLayout = null;
    if (layout != "" && layout != null) {
      setLayout = layout;
    } else {
      setLayout = "center";
    }
    return Swal.fire({
      position: setLayout,
      icon: "success",
      title: "Success",
      html: data,
      allowOutsideClick: false,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      return result;
    });
  }

  function notif_warning(data,layout){
    var setLayout=null;
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='topRight';
    }
    new Noty({
      theme: ' alert alert-warning alert-styled-left p-0 bg-white',
      text: data,
      type: 'warning',
      progressBar: false,
      timeout: 2500,
      layout: setLayout,
      closeWith: ['button']
    }).show();
  }

  function notiferror(data,layout){
    var setLayout = null;
    if (layout != "" && layout != null) {
      setLayout = layout;
    } else {
      setLayout = "center";
    }
    return Swal.fire({
      position: setLayout,
      icon: "error",
      title: "Oops..",
      html: data,
      allowOutsideClick: false,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      return result;
    });
  }

  function notiferror_a(data,layout){
    if (layout != "" && layout != null) {
      setLayout = layout;
    } else {
      setLayout = "center";
    }
    return Swal.fire({
      position: setLayout,
      icon: "error",
      title: "Oops..",
      html: data,
      allowOutsideClick: false,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      return result;
    });
  }

  function notifprimary(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-styled-left alert-arrow-left alert-primary'
    });
  }

  function notifdanger(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-styled-left alert-arrow-left alert-danger'
    });
  }

  function notifwarning(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-styled-left alert-arrow-left alert-warning'
    });
  }

  function notifcustom(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-styled-left alert-styled-custom alpha-teal text-teal-800 border-teal'
    });
  }

  function notifdefaultprimary(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-bordered alert-primary'
    });
  }

  function notifdefaultdanger(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-bordered alert-danger'
    });
  }
  function notifdefaultwarning(data,header,layout){
    if(layout!='' && layout!=null){
      setLayout=layout;
    }else{
      setLayout='top-right';
    }
    $.jGrowl(data, {
      header: header,
      position: setLayout,
      theme: 'alert-bordered alert-warning'
    });
  }
