import React, { Component } from 'react'
import Table, { TableProps, TableState } from 'adios/Table';

interface TableBillingAccountServicesProps extends TableProps {
  showHeader: boolean,
  showFooter: boolean
}

interface TableBillingAccountServicesState extends TableState {
}

export default class TableBillingAccountServices extends Table<TableBillingAccountServicesProps, TableBillingAccountServicesState> {
  static defaultProps = {
    ...Table.defaultProps,
    itemsPerPage: 15,
    formUseModalSimple: true,
    model: 'CeremonyCrmApp/Modules/Core/Services/Models/Service',
  }

  props: TableBillingAccountServicesProps;
  state: TableBillingAccountServicesState;

  constructor(props: TableBillingAccountServicesProps) {
    super(props);
    this.state = this.getStateFromProps(props);
  }

//   getStateFromProps(props: TableBillingAccountServicesProps) {
//     return {
//       ...super.getStateFromProps(props),
//     }
//   }

//   renderForm(): JSX.Element {
//     let formDescription = this.getFormProps();
//     return <FormActivity {...formDescription}/>;
//   }
}