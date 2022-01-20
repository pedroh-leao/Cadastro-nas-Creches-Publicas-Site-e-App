/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package gerais;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.ObjectMapper;

/**
 *
 * @author joao pedro e pedro henrique
 */
public class Resposta {


    private int cod;
    private Object info;

    public int getCod() {
        return cod;
    }

    public void setCod(int cod) {
        this.cod = cod;
    }

    public Object getInfo() {
        return info;
    }

    public void setInfo(Object info) {
        this.info = info;
    }

    public Resposta(int cod, Object info) {
        this.cod = cod;
        this.info = info;
    }

    public String toString(){
        ObjectMapper mascara = new ObjectMapper();
        
        try{
            return mascara.writeValueAsString(this);
        }catch(JsonProcessingException ex){
            return "{\"cod\":500, \"informacao\":\"erro no JSON\" }";
        }
    }
    
}
