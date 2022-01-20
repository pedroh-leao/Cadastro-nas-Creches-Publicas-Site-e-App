package com.exemplo.app.placeholder;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.exemplo.app.R;

import java.util.ArrayList;

// se der errado, o erro é no tipo de ArrayAdapter
//classe que define o comportamento e informações de cada um dos itens da lista de eventos
public class ItemListaEvento extends ArrayAdapter<String> {

    private Context contextoPai;
    private ArrayList<String> notificacoes;

    private static class ViewHolder{
        private TextView mensagem;
    }

    public ItemListaEvento(Context contexto, ArrayList<String> dados){
        super(contexto, R.layout.item_lista_eventos,dados);

        this.contextoPai = contexto;
        this.notificacoes = dados;
    }

    @NonNull
    @Override
    public View getView(int indice, @Nullable View convertView, @NonNull ViewGroup parent) {
        //return super.getView(indice, convertView, parent);

        String mensagem = notificacoes.get(indice);
        ViewHolder novaView;

        final View resultado;

        //1° caso é quando a lista está sendo montada pela primeira vez
        if(convertView == null){

            novaView = new ViewHolder();

            LayoutInflater inflater = LayoutInflater.from(getContext());
            convertView = inflater.inflate(R.layout.item_lista_eventos, parent, false);

            //linkando com o componente do XML
            novaView.mensagem = (TextView) convertView.findViewById(R.id.mensagem);

            resultado = convertView;
            convertView.setTag(novaView);

        }else{
            //2° caso é quando o item está sendo modificado
            novaView = (ViewHolder) convertView.getTag();
            resultado = convertView;
        }

        //Vamos setar os valores de cada campo
        novaView.mensagem.setText(mensagem);

        return resultado;
    }
}
