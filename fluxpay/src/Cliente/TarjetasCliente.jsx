import React from 'react';
import Cards from 'react-credit-cards-2';
import 'react-credit-cards-2/dist/es/styles-compiled.css';

const TarjetasCliente = ({ number, expiry, cvc, name, focused }) => {
  return (
    <div>
      <Cards
        number={number}
        expiry={expiry}
        cvc={cvc}
        name={name}
        focused={focused}
      />
    </div>
  );
}

export default TarjetasCliente;