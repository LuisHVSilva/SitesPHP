//Função para limitar a quantidade de vezes que o script de animação irá rodar
const debounce = function(func, wait, immediate){
    let timeout;
    return function(...args){
        const context = this;
        const later = function(){
            timeout = null;
            if (!immediate) func.apply(context. args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};


// 1 - Selecionar elementos que devem ser animados
const target = document.querySelectorAll('[data-anime]');

// 2 - Definir a classe que é adicionada durante a animação
const animationClass = 'animate';
// 3 - Criar função animação
function animeScroll(){
    
    // 3.1 - Verificar distância entre a barra de scroll e o topo do site
    const windowTop = window.pageYOffset + ((window.innerHeight * 3) / 4);
    //Chamar todos os elementos existentes e a distância em relação ao topo    
    target.forEach(function(element){
        
        // 3.2 - Verificar se a distância em '3.1' + offset é maior do que a distância entre o elemento e o topo da página
        if(windowTop > element.offsetTop){
            // 3.3 - Se verdadeiro, adicionar classe de animação, se falso, remover
            element.classList.add(animationClass);
        } else{
            element.classList.remove(animationClass);
        };
    });
};

animeScroll();

if(target.length){
    // 4 - Ativar a função de animação toda vez que o usuário utilizar o scroll
    window.addEventListener('scroll', debounce(function(){
        animeScroll();
    }, 200))
}


