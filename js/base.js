var usuarioObj = function() 
{
  var that = {};

  that.acessar = function() 
  {
    alert('Em breve');
  }

  that.cadastrar = function() 
  {
    alert('Em breve');
  }

  that.init = function () 
  {
    addEvent('acessar', 'click', that.acessar);
    addEvent('cadastrar', 'click', that.cadastrar);
    
	  mTabNav.showTab('vw_principal');
  
	  return that; 
  };
  
  return that.init();
};

addOnLoadManager(
  function() {
    ycomm.defaultMethod='GET';
    usuario = usuarioObj();
  }
);