{{-- 
    Este arquivo é compartilhado! 
    Ele assume que as variáveis 'descricao' e 'ativa' existem no x-data do modal pai.
--}}

<x-meus-components.input-text 
    name="descricao" 
    label="Descrição do Cargo" 
    placeholder="Ex: Gerente - Consultor - " 
    col="col-span-12" 
    x-model="descricao" {{-- O Alpine vincula dinamicamente aqui --}}
    x-effect="if (open) setTimeout(() => $el.focus(), 100)"
    required 
/>

<x-meus-components.toggle 
    name="ativa" 
    label="Cargo Ativo?" 
    modelAlpine="ativa" {{-- Nosso toggle inteligente já aceita isso nativamente! --}}
/>