import React, { Component } from 'react'
import 'materialize-css/dist/css/materialize.min.css'
import M from 'materialize-css'
import './icons.css'


class TabelaArquivos extends Component {

    geraListaArquivos = () => {
        let lista = []
        let arquivos = this.props.arquivos
        if(arquivos.length == 0){
            return lista
        }
        for (const [index, arquivo] of arquivos.entries()) {
            let id = arquivo.id
            lista.push(
                <tr key={id}>
                    <td>{arquivo.nomeoriginal}</td>
                    <td>
                        <a onClick={(e) => this.props.ativaDownload(e, id)} href="#">
                            <i className="material-icons">file_download</i>
                        </a>
                        &nbsp;
                        &nbsp;

                        <a onClick={(e) => this.props.ativaDownloadGDocs(e, id)} href="#">
                            <i className="material-icons">cloud_download</i>
                        </a>
                        &nbsp;
                        &nbsp;

                        <a
                        onClick={(e) => this.props.abreModalDeleta(e, id)}
                        style={{
                            "color": "red"
                        }} href="#">
                            <i className="material-icons">delete_forever</i>
                        </a>

                    </td>
                </tr>
            )
        }
        return lista
    }

    render() {
        return (
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Arquivo</th>
                            <th>Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        {this.geraListaArquivos()}
                    </tbody>
                </table>



            </div>
        )
    }


}

export default TabelaArquivos