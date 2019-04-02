window.Eos = require('eosjs');

function signin () {
  console.log("you signin scatter wallet");
  if (typeof scatter === "undefined"){
    console.log("Scatter is not installed");
    return false;
  }else{
      const network = { // EOS Main Net
       blockchain: 'eos',
       host: 'bp.cryptolions.io',
       port: 443,
       protocol: 'https',
       chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'
     };
     const requiredFields = {accounts:[network]};
     console.log("scatter wallet found");
     scatter.getIdentity(requiredFields).catch(err => {
       if (err.type == "locked"){
          console.log("scatter is lock");
          return false;
        }
     });

     signout();
  }
}

function signout(){
  console.log("you press sign out");
  scatter.forgetIdentity();
}

function pay (){
  console.log("you press pay");
  var eos = getEos();
  eos.transfer('fundurianyes', 'goodgoodgood', '0.00 EOS', 'test');
}

function getblock(){
  console.log("you press get block");
  var eos = getEos();
  eos.getBlock(1).then(result => {console.log(result)});
}

function getinfo(){
  console.log("you press get info");
  var eos = getEos();
  eos.getInfo((error, result) => { console.log(error, result) });
}

function getEos(){
  var scatterNetwork = {
    blockchain: 'eos',
    host: 'bp.cryptolions.io',
    port: 443,
    protocol: 'https',
    chainId: "aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906"
  }
  var config = {
    broadcast: true,
    sign: true,
    chainId: "a628a5a6123d6ed60242560f23354c557f4a02826e223bb38aad79ddeb9afbca"
  }
  return scatter.eos(scatterNetwork, Eos, config);
}
