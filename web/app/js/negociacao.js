// objeto da negociacao
function Negociacao(context) {
	// armazena o contexto
	var self = this;
	// seta o contexto
	var context = context;
	
	// atributos do objeto
	self.id = null;
	self.id_contrato = null;
	self.id_credor = null;
	self.id_campanha = null;
	self.data_negociacao = '';
	self.tipo = '';
	self.subtotal = 0;
	self.desconto = 0;
	self.receita = 0;
	self.total = 0;
	self.desconto_encargos = 0;
	self.desconto_principal = 0;
	self.desconto_honorarios = 0;
	self.desconto_total = 0;
	self.observacao = '';
	
	// configura o máximo de desconto permitido
	self.desconto_max_encargos = 75;
	self.desconto_max_principal = 75;
	self.desconto_max_honorarios = 75;
	self.desconto_max_total = 9999;
	
	// carrega os dados no objeto
	self.load = () => {
		self.id = $('#negociacao-id', context).val() || null;
		self.id_contrato = $('#negociacao-contrato', context).val() || null;
		self.id_credor = $('#negociacao-credor', context).val() || null;
		self.id_campanha = $('#negociacao-campanha', context).val() || null;
		self.data_negociacao = $('#negociacao-data', context).val() || '';
		self.tipo = $('#negociacao-tipo', context).val() || 0;
		self.subtotal = $('#negociacao-subtotal', context).data('value') || 0;
		self.desconto = $('#negociacao-desconto', context).data('value') || 0;
		self.receita = $('#negociacao-receita', context).data('value') || 0;
		self.total = $('#negociacao-total', context).data('value') || 0;

		// atualiza os valores de desconto
		self.loadDesconto();
	}
	
	// carrega os valores de desconto
	self.loadDesconto = () => {
		self.desconto_encargos = $('#desconto_encargos-disp', context).val() || 0;
		self.desconto_principal = $('#desconto_principal-disp', context).val() || 0;
		self.desconto_honorarios = $('#desconto_honorarios-disp', context).val() || 0;
		self.desconto_total = $('#desconto_total-disp', context).val() || 0;
		self.observacao = $('#negociacao-observacao', context).val() || '';
		
		// remove a mascara dos descontos
		self.desconto_encargos = accounting.unformat(self.desconto_encargos, '.');
		self.desconto_principal = accounting.unformat(self.desconto_principal, '.');
		self.desconto_honorarios = accounting.unformat(self.desconto_honorarios, '.');
		self.desconto_total = accounting.unformat(self.desconto_total, '.');
	}
	
	// valida o limite de desconto concedido
	self.validarDesconto = () => {
		if (self.desconto_encargos > 0 && self.desconto_encargos > self.desconto_max_encargos) {
			toastr.warning('O desconto dos encargos é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_principal > 0 && self.desconto_principal > self.desconto_max_principal) {
			toastr.warning('O desconto principal é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_honorarios > 0 && self.desconto_honorarios > self.desconto_max_honorarios) {
			toastr.warning('O desconto dos honorários é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_total > 0 && self.desconto_total > self.desconto_max_total) {
			toastr.warning('O desconto total é superior ao limite permitido.');
			return false;
		}
		
		return true;
	}
	
	// recalcula a negociacao
	self.calcularNegociacao = () => {
		// reseta os valores da negociacao
		self.subtotal = 0;
		self.total = 0;
		self.desconto = 0;
		self.receita = 0;
		
		// atualiza cada parcela do contrato
		$('.panel-calculo .contrato-parcela').each(function() {
			// variaveis de controle dos valores da parcela
			let [total, receita, encargos, principal] = [0, 0, 0, 0];
			
			// variaveis de controle dos descontos
			let [encargosDesconto, principalDesconto] = [0, 0];
			
			// soma o total
			principal = Number($(this).find('td.principal').data('value'));
			
			// soma os encargos
			encargos += Number($(this).find('td.multa').data('value'));
			encargos += Number($(this).find('td.juros').data('value'));
		
			// calcula o desconto dos encargos
			if (self.desconto_encargos > 0) {
				self.desconto += Number(encargos * (self.desconto_encargos / 100));
				encargosDesconto = Number(encargos * (self.desconto_encargos / 100));
			}
			// calcula o desconto principal
			if (self.desconto_principal > 0) {
				self.desconto += Number(principal * (self.desconto_principal / 100));
				principalDesconto = Number(principal * (self.desconto_principal / 100));
			}
			// calcula os honorarios
			if (self.desconto_honorarios > 0) {
				/*self.desconto += self.receita * (self.desconto_honorarios / 100);
				self.receita = self.receita - (self.receita * (self.desconto_honorarios / 100));*/
			}
			
			let taxaHonorario = Number($(this).find('td.honorario').data('value'));		
			receita = ((principal - principalDesconto) + (encargos - encargosDesconto)) * (taxaHonorario / 100);
			
			// calcula o total da parcela
			total = Math.floor((principal + encargos + receita) * 100) / 100;
			
			// arredonda a receita para baixo
			receita = Math.floor(receita * 100) / 100;
			
			// atualiza os valores na listagem
			$(this).find('td.honorario').text(accounting.formatMoney(receita, 'R$ ', 2, '.', ','));
			$(this).find('td.total').text(accounting.formatMoney(total, 'R$ ', 2, '.', ','));
			
			// seta o total da negociacao
			self.receita += receita;
			self.subtotal += total;
			self.desconto += encargosDesconto + principalDesconto;
		});
		
		// calcula o total
		/*if (self.desconto_total > 0) {			
			self.total -= self.desconto_total;
			self.desconto += self.desconto_total;
		}*/
		
		// atualiza os totais da parcela
		$('.panel-calculo .parcelas-total-honorarios').data('value', self.receita).html(`<b>${accounting.formatMoney(self.receita, 'R$ ', 2, '.', ',')}</b>`);
		$('.panel-calculo .parcelas-total-total').data('value', self.subtotal).html(`<b>${accounting.formatMoney(self.subtotal, 'R$ ', 2, '.', ',')}</b>`);
		
		// calcula o total
		self.total = Math.floor((self.subtotal - self.desconto) * 100) / 100;
		
		// atualiza os valores da self
		$('#negociacao-subtotal').data('value', self.subtotal).text(accounting.formatMoney(self.subtotal, 'R$ ', 2, '.', ','));
		$('#negociacao-desconto').data('value', self.desconto).text(accounting.formatMoney(self.desconto, 'R$ ', 2, '.', ','));
		$('#negociacao-receita').data('value', self.receita).text(accounting.formatMoney(self.receita, 'R$ ', 2, '.', ','));
		$('#negociacao-total').data('value', self.total).text(accounting.formatMoney(self.total, 'R$ ', 2, '.', ','));
		
		return true;
	}
};

// eventos da página
$(document).ready(function() {
	// cria o objeto de negociacao
	let negociacao = new Negociacao($('.panel-calculo'));
	
	// carrega os dados na negociacao
	negociacao.load();
	// calcula os descontos
	negociacao.calcularNegociacao();
	
	// salva a negociacao
	$('body').on('click', '#salvar-negociacao', function() {
		// carrega os dados na negociacao
		negociacao.load();
		
		// calcula e valida o desconto antes de enviar a requisicao
		if (negociacao.validarDesconto() && negociacao.calcularNegociacao()) {
			// envia a requisicao para salvar a negociacao
			$.post(BASE_PATH + 'negociacao/salvar', {Negociacao: negociacao}, function(response) {
				let retorno = JSON.parse(response);
				
				if (retorno.success == true) {
					toastr.success('A negociação foi salva com sucesso.');
					
					// altera o status da negociacao
					$('.negociacao-status').removeClass('label-warning').addClass('label-info').text('Em Negociação');
				} else {
					toastr.error('Houve um erro interno ao tentar salvar a negociação.');
				}
			});
		}		
	});
	
	// calcula os descontos da negociacao
	$('body').on('change', '.negociacao-descontos', function() {
		// carrega os dados de desconto
		negociacao.loadDesconto();
		// valida os descontos
		negociacao.validarDesconto();
		// calcula os descontos
		negociacao.calcularNegociacao();
	});
});


