// JavaScript Document
numero_de_dicas = 10;
dicaAtual = 1;
dicaAnterior = numero_de_dicas;
/**/
window.addEvent('domready', function() {
	mudar_dica();
	mudar_botao();	
});
function proxima_dica () {
	dicaAnterior = dicaAtual;
	if(dicaAtual==numero_de_dicas)
	dicaAtual=1;
	else
	dicaAtual++;
	
	mudar_dica();
	mudar_botao();	
}
function mudar_botao() {
	var botao_dicas = $("botao_dicas");
	
	if(dicaAtual==numero_de_dicas) {
		dicaProxima=1;
		botao_dicas.value=txt_tips_button_end;
	} else {
		dicaProxima = dicaAtual;
		dicaProxima++;
		botao_dicas.value=txt_tips_button+dicaProxima+"";
	}

}
//mudar_dica();
function mudar_dica() {
	/*UL e LI*/
	var dicaAn = $("dica_"+dicaAnterior);
	var dicaAt = $("dica_"+dicaAtual);
	var dicaP = $("dica_"+dicaAtual).getElements('p');

	dicaAn.set('morph', {
		duration: 1000
	});
	dicaAt.set('morph', {
		duration: 1000
	});
	dicaP.set('morph', {
		duration: 1000
	});

	dicaAn.morph({
		'line-height': '0',
		'color': 'transparent',
		'background-color': 'transparent',
		'padding': '0',
		'display': 'none'
	});
	dicaAt.morph({
		'line-height': '25px',
		'color': '#000',
		'background-color': '#EEE',
		'padding': '14px',
		'display': 'block'
	});
	dicaP.morph({

		'background-color': '#EEE',

	});
}
