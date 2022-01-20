package com.exemplo.app;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.exemplo.app.placeholder.ItemListaEvento;


import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.sql.SQLOutput;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class visualizarNotificacoes extends AppCompatActivity {



    private ListView listaNotificacoes;
    private int totalNotificacoes;
    private int notificacaoAtual;
    private ArrayList<String> notificacaoLista;
    private ItemListaEvento adapter;


    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_visualizar_notificacoes);

        listaNotificacoes = (ListView) findViewById(R.id.listNotificacao);

        requestListaNotificacoes(GlobalVar.cpf);

    }

    private void requestListaNotificacoes(String cpfUser){
        notificacaoLista = new ArrayList<String>();
        RequestQueue pilha = Volley.newRequestQueue(this);
        String url = GlobalVar.urlServidor +"usuario";

        StringRequest jsonRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {

            //onResponse é executado assim que o servidor entrega o resultado do processamento
            @Override
            public void onResponse(String response) {
                //o parametro response é o resultado enviado do servidor para o app
                System.out.println(response);
                try {
                    JSONObject resposta = new JSONObject(response);

                    //200 indica sucesso
                    if (resposta.getInt("cod") == 200) {
                        JSONArray dadosJSON = resposta.getJSONArray("info");
                        for(int i = 0; i<dadosJSON.length(); i++){
                            JSONObject obj = dadosJSON.getJSONObject(i);
                            notificacaoLista.add(obj.getString("mensagem"));
                        }
                        carregaEventosLista();

                    } else {
                        //erro... que foi relatado pelo servidor
                        Toast.makeText(visualizarNotificacoes.this, resposta.getString("info"), Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException ex) {
                    //erro no formato JSON enviado pelo servidor
                    ex.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                error.printStackTrace();
                Toast.makeText(visualizarNotificacoes.this, "Verifique a sua conexão de internet: "+error, Toast.LENGTH_LONG).show();
            }
        }){
            protected Map<String, String> getParams(){
                Map<String, String> parametros = new HashMap<>();
                parametros.put("servico", "pesquisaNotificacao");
                parametros.put("cpf", cpfUser);


                return parametros;
            }
        };
        //coloca a requisição na pilha de execução
        pilha.add(jsonRequest);
    }

    private void carregaEventosLista(){


        adapter = new ItemListaEvento(getApplicationContext(), notificacaoLista);
        listaNotificacoes.setAdapter(adapter);

    }

}