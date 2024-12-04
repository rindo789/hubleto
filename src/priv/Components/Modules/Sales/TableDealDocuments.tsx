import React, { Component } from 'react'
import Table, { TableProps, TableState } from 'adios/Table';

interface TableDealDocumentsProps extends TableProps {}
interface TableDealDocumentsState extends TableState {}

export default class TableDealDocuments extends Table<TableDealDocumentsProps, TableDealDocumentsState> {
  static defaultProps = {
    ...Table.defaultProps,
    itemsPerPage: 15,
    formUseModalSimple: true,
    model: 'CeremonyCrmApp/Modules/Sales/Sales/Models/DealDocument',
  }

  props: TableDealDocumentsProps;
  state: TableDealDocumentsState;

  constructor(props: TableDealDocumentsProps) {
    super(props);
    this.state = this.getStateFromProps(props);
  }

  getStateFromProps(props: TableDealDocumentsProps) {
    return {
      ...super.getStateFromProps(props),
    }
  }
}