import { Modal, Button, Form } from "react-bootstrap";
import { useState, useEffect } from "react";
import { FaSave } from "react-icons/fa";

const ClientDataForm = ({
  show, handleClose,title,label,type = "text",value,onSave,
}) => {
  const [inputValue, setInputValue] = useState("");

  useEffect(() => {
    setInputValue(value || "");
  }, [value]);

  const handleSubmit = () => {
    onSave(inputValue);
    handleClose();
  };

  return (
    <Modal show={show} onHide={handleClose} centered backdrop="static">
      <Modal.Header closeButton className="border-0">
        <Modal.Title className="fw-bold">{title}</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <Form>
          <Form.Label className="fw-semibold">{label}</Form.Label>

          <Form.Control
            type={type}
            value={type !== "file" ? inputValue : undefined}
            onChange={(e) =>
              type === "file"
                ? onSave(e.target.files[0])
                : setInputValue(e.target.value)
            }
            className="rounded-3"
          />
        </Form>
      </Modal.Body>

      {type !== "file" && (
        <Modal.Footer className="border-0">
          <Button
            variant="secondary"
            onClick={handleClose}
            className="rounded-pill"
          >
            Cancelar
          </Button>

          <Button
            variant="primary"
            onClick={handleSubmit}
            className="rounded-pill"
          >
            <FaSave className="me-2" />
            Guardar
          </Button>
        </Modal.Footer>
      )}
    </Modal>
  );
};

export default ClientDataForm;