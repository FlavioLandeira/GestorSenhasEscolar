// Funcionalidade do Menu Hamburger (para dispositivos móveis)
const hamburger = document.querySelector(".hamburger");
const navbar = document.querySelector(".navbar");

hamburger.addEventListener("click", () => {
  navbar.classList.toggle("active");
});

// Exemplo de contador de senhas
let senhaAtual = 1; // Número da senha atual
const senhaDisplay = document.querySelector("#senhaDisplay");

function gerarSenha() {
  senhaDisplay.innerHTML = `Sua senha: ${senhaAtual}`;
  senhaAtual++;
}

// Exemplo de interação com o botão "Retirar Senha"
const botaoSenha = document.querySelector("#botaoSenha");
if (botaoSenha) {
  botaoSenha.addEventListener("click", gerarSenha);
}

// Adicionando animações ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    const sections = document.querySelectorAll("section");
    sections.forEach(section => {
      section.classList.add("animated");
    });
  }, 500);
});
