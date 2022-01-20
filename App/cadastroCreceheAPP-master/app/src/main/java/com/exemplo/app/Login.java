package com.exemplo.app;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.app.Activity;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.StringReader;
import java.util.HashMap;
import java.util.Map;

import at.favre.lib.crypto.bcrypt.BCrypt;

public class Login extends AppCompatActivity {
    private Button loginBtn, cadastrarBtn;
    private EditText cpfTxt, senhaTxt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        loginBtn = (Button) findViewById(R.id.loginBtn);
        cadastrarBtn = (Button) findViewById(R.id.cadastroBtn);
        cpfTxt = (EditText) findViewById(R.id.cpfLogin);
        senhaTxt = (EditText) findViewById(R.id.senhaLogin);

        //responsavel por implementar todos os eventos de botoes
        cadastroEventos();

        configuraPermissoes();
    }

    private void cadastroEventos(){

        /* falta trabalhar com a sessão da conta do usuário logado */
        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String cpf = cpfTxt.getText().toString();
                String senha = senhaTxt.getText().toString();
                requestLoginEvento(cpf, senha);
            }
        });

        // evendo do botão cadastrar
        cadastrarBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //Intent trocaAct = new Intent(Login.this, cadastro2.class);

                //pedimos para iniciar a activity passada como parâmetro
                //startActivity(trocaAct);

                Toast.makeText(Login.this, "O cadastro é feito apenas pelo site!", Toast.LENGTH_LONG).show();
            }
        });

    }

    private void loginSucced(){
        Toast.makeText(Login.this, "Logado com sucesso", Toast.LENGTH_SHORT).show();

        Intent trocaAct = new Intent(Login.this, MainActivity.class);

        //pedimos para iniciar a activity passada como parâmetro
        startActivity(trocaAct);
        finish();
    }

    private void requestLoginEvento(String cpfUser, String senhaUser){
        RequestQueue pilha = Volley.newRequestQueue(this);
        String url = GlobalVar.urlServidor +"usuario";

        StringRequest jsonRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {

            //onResponse é executado assim que o servidor entrega o resultado do processamento
            @Override
            public void onResponse(String response) {
                //o parametro response é o resultado enviado do servidor para o app

                try {
                    JSONObject resposta = new JSONObject(response);

                    //200 indica sucesso
                    if (resposta.getInt("cod") == 200) {
                        JSONObject obj = resposta.getJSONObject("info");
                        String senhaCriptografada = obj.getString("senha");

                        BCrypt.Result result = BCrypt.verifyer().verify(senhaUser.toCharArray(), senhaCriptografada);


                        if(result.verified){
                            GlobalVar.cpf = cpfUser;
                            GlobalVar.nome = obj.getString("nome");
                            loginSucced();
                        }else{
                            Toast.makeText(Login.this, "Senha incorreta!", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        //erro... que foi relatado pelo servidor
                        Toast.makeText(Login.this, resposta.getString("info"), Toast.LENGTH_SHORT).show();
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
                Toast.makeText(Login.this, "Verifique a sua conexão de internet: "+error, Toast.LENGTH_LONG).show();
            }
        }){
            protected Map<String, String> getParams(){
                Map<String, String> parametros = new HashMap<>();
                parametros.put("servico", "loginResponsavel");
                parametros.put("cpf", cpfUser);
                parametros.put("senha", senhaUser);

                return parametros;
            }
        };
        //coloca a requisição na pilha de execução
        pilha.add(jsonRequest);
    }

    private void configuraPermissoes(){
        if(ContextCompat.checkSelfPermission(getBaseContext(), Manifest.permission.INTERNET) != PackageManager.PERMISSION_GRANTED){
            ActivityCompat.requestPermissions(Login.this, new String[]{Manifest.permission.INTERNET}, 0);
        }else{
            //Toast.makeText(Login.this, "Conectado na internet", Toast.LENGTH_LONG).show();
        }
    }
}