CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ra CHAR(5) NOT NULL UNIQUE CHECK (ra REGEXP '^[0-9]{5}$');
    serie VARCHAR(2) NOT NULL,
    turma VARCHAR(10) NOT NULL,
    telefone VARCHAR(15) NOT NULL CHECK (telefone REGEXP '^\\([0-9]{2}\\)[0-9]{4,5}-[0-9]{4}$'),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);