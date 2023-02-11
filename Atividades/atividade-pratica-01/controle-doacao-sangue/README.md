# Atividade Prática 1
## _Edilaine Lucia Pena_

Essa atividade visa exercitar os conceitos vistos na disciplina,
e segue o esquema do BD proposto pelo professor.

## Tecnologias Utilizadas

- PHP - Como Backend;
- Laravel - Framework;
- SQLite - BD;
- VSCode - IDE;
- Postman - Para realizar as requisições e testar a API (segue o padrão REST);

## Configuração do Ambiente

- Clonar esse repositório;
- Baixar e instalar o Postman
- Baixar e instalar o PHP (versão >= 8.0);
- Baixar e instalar o Composer (gerenciador de pacotes);
- Instalar o Laravel via Composer;
- Criar o arquivo do BD ('database.sqlite') na raíz da pasta 'database';
- Rodar as migrations definidas no projeto
    ```sh
    php artisan:migrate
    ```
- Subir o servidor embutido do  Laravel
    ```sh
    php artisan serve
    ```
- Fazer as requisições via Postman, como segue abaixo:
- 
**O arquivo _'tests/API_WEB_I.postman_collection.json'_ pode ser importado para o Postman. Ele contém scripts prontos para testar todos os endpoints.**

## EndPoints

**nomeTabela - Consultar no arquivo _'routes/api.php'_**

- READ - Todos
    ```sh
    /api/nomeTabela - MÉTODO GET
    ```
- READ - Por ID
    ```sh
    /api/nomeTabela/{id} - MÉTODO GET
    ```
- READ - Por NOME (Quando aplicável)
    ```sh
    /api/nomeTabela/pornome/{nome} - MÉTODO GET
    ```
- CREATE
    ```sh
    /api/nomeTabela - MÉTODO POST
    ```
- UPDATE
    ```sh
    /api/nomeTabela/{id} - MÉTODO PUT/PATCH
    ```
- DELETE
    ```sh
    /api/nomeTabela/{id} - MÉTODO DELETE
    ```
**Obs: Para que a validação do Laravel funcione e retorne os dados de erros em JSON, devemos adicionar a propriedade 'Accept' com o valor 'application/json' ao cabeçalho de cada requisição.**

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
