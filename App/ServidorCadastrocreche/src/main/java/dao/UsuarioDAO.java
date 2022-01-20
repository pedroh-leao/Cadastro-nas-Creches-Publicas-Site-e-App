/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package dao;

import gerais.FabricaConexao;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import model.Notificacao;
import model.Usuario;
//import org.mindrot.jbcrypt.BCrypt;

/**
 *
 * @author joao pedro e pedro henrique
 */
public class UsuarioDAO {

    /*public static boolean verifyHash(String senhaDigitada, String senhaCriptografada){
        return BCrypt.checkpw(senhaDigitada, senhaCriptografada);
    }*/
    
    public static Usuario loginUser(String Cpf, String Senha) {

        //String sql = "SELECT Cpf, Senha FROM tb_responsavel WHERE Cpf='" + Cpf + "' AND Senha='" + Senha + "'";
        String sql = "SELECT Cpf, Senha, Nome FROM tb_responsavel WHERE Cpf='" + Cpf + "'";
        Usuario temp = null;

        try (Connection con = FabricaConexao.criaConexao()) {
            PreparedStatement trans = con.prepareStatement(sql);
            /*
            trans.setString(1, Cpf);
            trans.setString(2, Senha);*/

            ResultSet tuplas = trans.executeQuery();
            
            while (tuplas.next()) { 
                /*String senhaHash = tuplas.getString("Senha");
                
                if(verifyHash(Senha, senhaHash)){
                    temp = new Usuario(tuplas.getString("Cpf"), tuplas.getString("Senha"));                
                }*/
                temp = new Usuario(tuplas.getString("Cpf"), tuplas.getString("Senha"), tuplas.getString("Nome"));
            }
        } catch (SQLException ex) {
            System.err.println("Erro de execução na consulta de usuário");
        }
        return temp;
    }

    public static ArrayList<Notificacao> PesquisaNotificacao(String Cpf) {
        String sql = "SELECT * FROM tb_notificacao WHERE tb_responsavel_Cpf='" + Cpf + "' order by id DESC";
        ArrayList<Notificacao> notificacoes = new ArrayList<>();

        try (Connection con = FabricaConexao.criaConexao()) {

            PreparedStatement consulta = con.prepareStatement(sql);
            ResultSet tuplas = consulta.executeQuery();

            while (tuplas.next()) {
                
                Notificacao temporario = new Notificacao(tuplas.getString("mensagem"));
                notificacoes.add(temporario);
            }

        } catch (SQLException ex) {
            System.out.println("Começo do erro ao consultar a notificação");
            ex.printStackTrace();
            System.out.println("Fim do erro ao consultar a notificação");
        }
        return notificacoes;
    }

    
}
