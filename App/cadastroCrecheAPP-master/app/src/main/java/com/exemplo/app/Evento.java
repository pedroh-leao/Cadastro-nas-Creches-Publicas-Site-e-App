package com.exemplo.app;

public class Evento {

    private String nome, nomeCrianca, cpf, senha;

    public Evento(String nome, String nomeCrianca, String cpf, String senha) {
        this.nome = nome;
        this.nomeCrianca = nomeCrianca;
        this.cpf = cpf;
        this.senha = senha;
    }

    public Evento(String cpf, String senha){
        this.cpf = cpf;
        this.senha = senha;
    }

    public String getSenha() {
        return senha;
    }

    public void setSenha(String senha) {
        this.senha = senha;
    }

    public String getCpf() {
        return cpf;
    }

    public void setCpf(String cpf) {
        this.cpf = cpf;
    }

    public String getNomeCrianca() {
        return nomeCrianca;
    }

    public void setNomeCrianca(String nomeCrianca) {
        this.nomeCrianca = nomeCrianca;
    }

    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }
}
