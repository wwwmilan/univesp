CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ra CHAR(7) NOT NULL UNIQUE,
    serie VARCHAR(2) NOT NULL,
    turma VARCHAR(10) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cadastroLivros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo TEXT(255) NOT NULL,
    subtitulo TEXT(255),
    autor VARCHAR(255) NOT NULL,
    editora VARCHAR(255),
    ano INT(4),
    edicao VARCHAR(50),
    isbn VARCHAR(20),
    descricao TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);