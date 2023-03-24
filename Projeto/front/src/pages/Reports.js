import React, {useState, useEffect} from "react";
import axios from 'axios';
import moment from 'moment';

import { Input } from "../components/Input";

import { sellsApi } from "../config";

function ReportsPage() {
  const [sells, setSells] = useState([]);

  useEffect(() => {
    axios.get(sellsApi).then(({data}) => {
      setSells(
        data.map(d => {
          const products = JSON.parse(d.products);
          // const date = new Date();
          const dateFormatted = moment(d.created_at).format('HH:mm:ss - DD/MM/YY');
          return {
            id: `${d.id}`,
            created_at: dateFormatted,
            products,
            paymentType: d.paymentType === 'Card' ? 'Cartão' : 'Dinheiro',
            quantity: products.length,
            value: products.reduce((acc, cur) => Number(acc) + Number(cur.price), 0),
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
            <th>Quantidade</th>
            <th>Valor (R$)</th>
            <th>Pagamento</th>
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
                  value={sell.quantity}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.value}
                />
              </td>
              <td>
                <Input
                  disabled={true}
                  value={sell.paymentType}
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
