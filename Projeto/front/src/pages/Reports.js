import React, {useState, useEffect} from "react";
import axios from 'axios';

import { Input } from "../components/Input";

import { sellsApi } from "../config";

function ReportsPage() {
  const [sells, setSells] = useState([]);

  const formatDate = (time) => {
    const t = new Date(time);
    return (
      ('0' + t.getDate()).slice(-2) +
      '/' +
      ('0' + (t.getMonth() + 1)).slice(-2) +
      '/' +
      t.getFullYear() +
      ' - ' +
      ('0' + t.getHours()).slice(-2) +
      ':' +
      ('0' + t.getMinutes()).slice(-2)
    );
  };

  useEffect(() => {
    axios.get(sellsApi).then(({data}) => {
      setSells(
        data.map(d => {
          // const products = JSON.parse(d.products);
          return {
            id: `${d.id}`,
            created_at: formatDate(d.created_at),
            paymentType: d.paymentType === 'Card' ? 'Cartão' : 'Dinheiro',
            quantity: d.quantity,
            value: d.value,
            // value: products?.reduce((acc, cur) => Number(acc) + Number(cur.price), 0),
          }
        })
      );
    });
  }, []);

  return (
    <div style={{width: '100%', overflowY: 'scroll', display: 'flex', padding: 15}}>
      <table>
        <thead>
          <tr>
            <th>Venda Nº</th>
            <th>Data</th>
            <th>Pagamento</th>
            <th>Quantidade</th>
            <th>Valor (R$)</th>
          </tr>
        </thead>
        <tbody>
          {sells.map((sell, index) => (
            <tr key={index}>
              <td>
                <Input
                  disabled={true}
                  value={sell.id}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.created_at}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.paymentType}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.quantity}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.value}
                />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default ReportsPage;
