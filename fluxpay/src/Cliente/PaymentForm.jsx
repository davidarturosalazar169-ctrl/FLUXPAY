import React, { useState } from "react";
import Cards from "react-credit-cards-2";
import "react-credit-cards-2/dist/es/styles-compiled.css";
import { Modal } from "react-bootstrap";

const PaymentForm = ({ show, handleCerrar, handlesave }) => {
  const [state, setState] = useState({
    number: "",
    expiry: "",
    cvc: "",
    name: "",
    focus: "",
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setState((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleInputFocus = (e) => {
    setState((prev) => ({
      ...prev,
      focus: e.target.name,
    }));
  };

const handleSubmit = (e) => {
  e.preventDefault();

  if (!state.number || !state.expiry) {
    alert("Completa los campos");
    return;
  }

  const clean = state.expiry.replace(/\D/g, ""); // solo números

  if (clean.length !== 4) {
    alert("Ingresa fecha como MMYY (ej: 0326)");
    return;
  }

  const month = clean.substring(0, 2);
  const year = clean.substring(2, 4);

  if (parseInt(month) < 1 || parseInt(month) > 12) {
    alert("Mes inválido");
    return;
  }

  let brand = "unknown";
  if (state.number.startsWith("4")) brand = "visa";
  else if (state.number.startsWith("5")) brand = "mastercard";
  else if (state.number.startsWith("3")) brand = "amex";

  handlesave({
    brand: brand,
    last4: state.number.slice(-4),
    exp_month: parseInt(month),
    exp_year: 2000 + parseInt(year),
    name: state.name,
  });

  setState({
    number: "",
    expiry: "",
    cvc: "",
    name: "",
    focus: "",
  });
};

  return (
    <Modal show={show} onHide={handleCerrar} centered>
      <Modal.Body>
        <Cards
          number={state.number}
          expiry={state.expiry}
          cvc={state.cvc}
          name={state.name}
          focused={state.focus}
        />

        <form className="mt-4" onSubmit={handleSubmit}>
          <input
            type="tel"
            name="number"
            className="form-control mb-3"
            placeholder="Card Number"
            value={state.number}
            onChange={handleInputChange}
            onFocus={handleInputFocus}
          />

          <input
            type="text"
            name="name"
            className="form-control mb-3"
            placeholder="Name on Card"
            value={state.name}
            onChange={handleInputChange}
            onFocus={handleInputFocus}
          />

          <div className="row">
            <div className="col">
              <input
                type="tel"
                name="expiry"
                className="form-control mb-3"
                placeholder="MM/YY"
                value={state.expiry}
                onChange={handleInputChange}
                onFocus={handleInputFocus}
              />
            </div>
          </div>

          <button type="submit" className="btn btn-primary w-100">
            Guardar
          </button>
        </form>
      </Modal.Body>
    </Modal>
  );
};

export default PaymentForm;