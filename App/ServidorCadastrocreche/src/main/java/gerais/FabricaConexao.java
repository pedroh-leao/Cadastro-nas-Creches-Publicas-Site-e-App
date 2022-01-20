package gerais;


import static java.lang.Class.forName;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

public class FabricaConexao {
    private static Connection conn;

    public static Connection criaConexao(){
        
        try {
            if(conn != null && !conn.isClosed()){
                return conn;
            } 

            Class.forName("com.mysql.jdbc.Driver");
            conn = DriverManager.getConnection("jdbc:mysql://200.18.128.50:3306/cadastrocreche", "cadastrocreche", "2021@Cadastrocreche");
        } catch (SQLException ex) {
            ex.printStackTrace();
        } catch (ClassNotFoundException ex){
            ex.printStackTrace();
        
        }

        return conn;

    }


}
