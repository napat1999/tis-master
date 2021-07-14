function formatEmpRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var pic = repo.id.replace(/\//g, '');
  var position='';
  if(repo.position) {
    position=repo.position;
  } else {
    if(repo.title) {
      position=repo.title;
    }
  }
  var markup=`
  <div class="row">
    <div align="center" class="col-sm-3">
      <img class='media-object img-thumbnail user-img' alt='User Picture'
        src='https://intranet.jasmine.com/hr/office/Data/` + pic + `.jpg'
        style='min-height:60px;height:60px;text-align:center'>
    </div>
    <div class="col-sm-8">
      <span>
        `+repo.id+`<br/>
        `+repo.thai_name+`<br/>
        `+position+`
      </span>
    </div>
  </div>
  `
  return markup;
}

function formatExpEmpRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var pic = repo.id.replace(/\//g, '');
  var position='';
  if(repo.position) {
    position=repo.position;
  } else {
    if(repo.title) {
      position=repo.title;
    }
  }
  var division='';
  if(repo.division) {
    division=repo.division;
  }
  var section='';
  if(repo.section) {
    section=repo.section;
  }
  var markup=`
  <div class="row">
    <div align="center" class="col-sm-3">
      <img class='media-object img-thumbnail user-img' alt='User Picture'
        src='https://intranet.jasmine.com/hr/office/Data/` + pic + `.jpg'
        style='min-height:60px;height:60px;text-align:center'>
    </div>
    <div class="col-sm-7">
      <span>
        `+repo.id+' '+repo.thai_name+`<br/>
        `+position+`<br/>
        `+'<span style="font-size:10px">'+division+' '+section+'</span>'+`
      </span>
    </div>
    <div class="col-sm-2">
      <span style="font-size:10px">
        `+repo.workdate+'<br/>วัน'+`
      </span>
    </div>
  </div>
  `
  return markup;
}

function formatRepoEmpSelection (repo) {
  if(typeof repo.id === "undefined" || typeof repo.thai_name === "undefined") {
    return repo.thai_name || repo.text;
  }
  return repo.id + " " + repo.thai_name;
}
