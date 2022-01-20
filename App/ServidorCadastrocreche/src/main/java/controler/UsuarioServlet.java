/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package controler;

import dao.UsuarioDAO;
import gerais.Resposta;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.Notificacao;
import model.Usuario;

/**
 *
 * @author joao pedro e pedro henrique
 */
@WebServlet(name = "UsuarioServlet", urlPatterns = {"/usuario"})
public class UsuarioServlet extends HttpServlet {

    private void login(HttpServletRequest request, PrintWriter out) {
        String cpf = request.getParameter("cpf");
        String senha = request.getParameter("senha");

        if (cpf == null || senha == null) {//verificando se os paramentros não são nulos
            out.println(new Resposta(403, "Para o login é preciso preencher todos os campos"));
        } else {
            Usuario temp = UsuarioDAO.loginUser(cpf, senha);

            if (temp == null) {
                out.println(new Resposta(404, "Usuário não cadastrado!"));
            } else {
                out.println(new Resposta(200, temp));
            }
        }

    }

    private void pesquisaNotificacao(HttpServletRequest request, PrintWriter out) {
        String cpf = request.getParameter("cpf");

        if (cpf == null) {//verificando se o paramentros é nulo
            out.println(new Resposta(403, "Para retornar a notificacao é preciso o cfp do responsável"));
        } else {
            ArrayList<Notificacao> temp = UsuarioDAO.PesquisaNotificacao(cpf);

            if (temp == null) {
                out.println(new Resposta(404, "Notificações não encontradas"));
            } else {
                out.println(new Resposta(200, temp));
            }
        }
    }

    /*private void informacoes(HttpServletRequest request, PrintWriter out){


    }*/
    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        try (PrintWriter out = response.getWriter()) {

            String servico = request.getParameter("servico");            

            if (servico == null) {
                //temos que enviar uma mensagem dizendo que o serviço não foi especificado
                out.println("Serviço não especificado");
            } else {
                switch (servico) {
                    case "loginResponsavel": {
                        login(request, out);
                    }
                    break;
                    case "pesquisaNotificacao": {
                        pesquisaNotificacao(request, out);
                    }
                    break;
                    default: {
                        out.println("Serviço não disponivel para o usuario");
                    }
                }
            }

        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

}
